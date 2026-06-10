<?php

namespace Tests\Feature\Api\V1\Orders;

use App\Models\Order;
use App\Models\RefOrderStatus;
use App\Models\RefPaymentMethod;
use App\Models\UserPaymentCard;
use App\Services\Payments\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class OrderPaymentTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    /** Seed reference rows (status id 3 = Completed) and an order owned by $owner. */
    private function seedOrder(int $userId): Order
    {
        DB::table('ref_order_statuses')->insert(['id' => 1, 'name' => 'Pending',   'code' => 'pending']);
        DB::table('ref_order_statuses')->insert(['id' => 3, 'name' => 'Completed', 'code' => 'completed']);
        RefPaymentMethod::create(['id' => 1, 'name' => 'Credit Card', 'code' => 'cc']);

        return Order::create([
            'user_id'           => $userId,
            'amount'            => 100.00,
            'discount_amount'   => 0,
            'status_id'         => 1,
            'payment_method_id' => 1,
            'notes'             => null,
        ]);
    }

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/orders')->assertStatus(401);
    }

    public function test_inactive_blocked_409(): void
    {
        $owner = $this->makeUserWithRole('company', ['is_active' => false]);
        $order = $this->seedOrder($owner->id);

        Sanctum::actingAs($owner);
        $this->getJson('/api/v1/orders/'.$order->id)->assertStatus(409);
    }

    public function test_non_admin_403_on_index_admin_ok(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));
        $this->getJson('/api/v1/orders')->assertStatus(403);

        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->getJson('/api/v1/orders')->assertOk();
    }

    public function test_owner_can_show_order(): void
    {
        $owner = $this->makeUserWithRole('company');
        $order = $this->seedOrder($owner->id);

        Sanctum::actingAs($owner);
        $this->getJson('/api/v1/orders/'.$order->id)
            ->assertOk()
            ->assertJsonPath('data.id', $order->id)
            ->assertJsonPath('data.user_id', $owner->id);
    }

    public function test_foreign_user_404_on_show(): void
    {
        $owner = $this->makeUserWithRole('company');
        $order = $this->seedOrder($owner->id);

        Sanctum::actingAs($this->makeUserWithRole('company'));
        $this->getJson('/api/v1/orders/'.$order->id)->assertStatus(404);
    }

    public function test_foreign_user_404_on_pay(): void
    {
        $owner = $this->makeUserWithRole('company');
        $order = $this->seedOrder($owner->id);

        Sanctum::actingAs($this->makeUserWithRole('company'));
        $this->postJson('/api/v1/orders/'.$order->id.'/pay')->assertStatus(404);
    }

    public function test_owner_can_get_pay_form(): void
    {
        $owner = $this->makeUserWithRole('company');
        $order = $this->seedOrder($owner->id);

        UserPaymentCard::create([
            'user_id'           => $owner->id,
            'card_number'       => '4242424242424242',
            'card_holder_name'  => 'John Doe',
            'payment_method_id' => 1,
        ]);

        Sanctum::actingAs($owner);
        $this->getJson('/api/v1/orders/'.$order->id.'/pay')
            ->assertOk()
            ->assertJsonPath('data.order_id', $order->id)
            ->assertJsonPath('data.total', 100)
            ->assertJsonCount(1, 'data.cards')
            ->assertJsonPath('data.cards.0.card_number', '4242')
            ->assertJsonPath('data.cards.0.primary', true)
            ->assertJsonPath('data.gateways.0.code', 'cc');
    }

    public function test_foreign_user_404_on_pay_form(): void
    {
        $owner = $this->makeUserWithRole('company');
        $order = $this->seedOrder($owner->id);

        Sanctum::actingAs($this->makeUserWithRole('company'));
        $this->getJson('/api/v1/orders/'.$order->id.'/pay')->assertStatus(404);
    }

    public function test_owner_pay_succeeds_creates_payment_and_flips_status(): void
    {
        $owner = $this->makeUserWithRole('company');
        $order = $this->seedOrder($owner->id);

        $this->mock(PaymentService::class, function ($m) {
            $m->shouldReceive('chargeCustomerWithProfile')
              ->once()
              ->andReturn([
                  'success'        => true,
                  'message'        => 'Payment processed successfully.',
                  'transaction_id' => 'TXN123',
                  'card'           => ['id' => 1],
              ]);
        });

        Sanctum::actingAs($owner);
        $this->postJson('/api/v1/orders/'.$order->id.'/pay')
            ->assertOk()
            ->assertJsonPath('status', 'succeeded')
            ->assertJsonPath('payment_id', fn ($v) => $v !== null);

        $this->assertDatabaseHas('order_payments', [
            'order_id'       => $order->id,
            'status'         => 'succeeded',
            'transaction_id' => 'TXN123',
        ]);
        $this->assertDatabaseHas('orders', [
            'id'        => $order->id,
            'status_id' => 3,
        ]);
    }

    public function test_owner_pay_fails_returns_422_and_no_payment(): void
    {
        $owner = $this->makeUserWithRole('company');
        $order = $this->seedOrder($owner->id);

        $this->mock(PaymentService::class, function ($m) {
            $m->shouldReceive('chargeCustomerWithProfile')
              ->once()
              ->andReturn(['error' => 'No primary payment card found for user.']);
        });

        Sanctum::actingAs($owner);
        $this->postJson('/api/v1/orders/'.$order->id.'/pay')
            ->assertStatus(422)
            ->assertJsonPath('status', 'failed');

        $this->assertDatabaseMissing('order_payments', ['order_id' => $order->id]);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status_id' => 1]);
    }

    public function test_owner_can_list_payments_history(): void
    {
        $owner = $this->makeUserWithRole('company');
        $order = $this->seedOrder($owner->id);

        DB::table('order_payments')->insert([
            'order_id'          => $order->id,
            'payment_method_id' => 1,
            'amount'            => 100.00,
            'status'            => 'succeeded',
            'transaction_id'    => 'TXN999',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        Sanctum::actingAs($owner);
        $this->getJson('/api/v1/orders/'.$order->id.'/payments')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.transaction_id', 'TXN999');
    }
}
