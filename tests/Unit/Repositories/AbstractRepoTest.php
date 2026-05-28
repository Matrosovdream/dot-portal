<?php

namespace Tests\Unit\Repositories;

use App\Models\RefCountryStates;
use App\Repositories\References\RefCountryStateRepo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Exercises the new unified methods on AbstractRepo (getModel, getFirst, count, exists,
 * applyFilter operators, mapItem null handling) using RefCountryStateRepo as the concrete vehicle.
 */
class AbstractRepoTest extends TestCase
{
    use RefreshDatabase;

    private RefCountryStateRepo $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new RefCountryStateRepo();

        RefCountryStates::create(['name' => 'Alabama',    'code' => 'AL', 'country_id' => 1]);
        RefCountryStates::create(['name' => 'California', 'code' => 'CA', 'country_id' => 1]);
        RefCountryStates::create(['name' => 'New York',   'code' => 'NY', 'country_id' => 2]);
    }

    public function test_get_model_returns_underlying_eloquent_instance(): void
    {
        $this->assertInstanceOf(RefCountryStates::class, $this->repo->getModel());
    }

    public function test_get_by_id_returns_mapped_item(): void
    {
        $first = RefCountryStates::first();

        $result = $this->repo->getByID($first->id);

        $this->assertIsArray($result);
        $this->assertEquals($first->id, $result['id']);
        $this->assertInstanceOf(RefCountryStates::class, $result['Model']);
        $this->assertEquals($first->name, $result['name']);
    }

    public function test_get_by_id_returns_null_for_missing_record(): void
    {
        $this->assertNull($this->repo->getByID(99999));
    }

    public function test_get_first_with_filter(): void
    {
        $result = $this->repo->getFirst(['code' => 'CA']);

        $this->assertNotNull($result);
        $this->assertEquals('California', $result['name']);
    }

    public function test_get_first_returns_null_when_no_match(): void
    {
        $this->assertNull($this->repo->getFirst(['code' => 'ZZ']));
    }

    public function test_count_returns_total_with_filter(): void
    {
        $this->assertEquals(3, $this->repo->count());
        $this->assertEquals(2, $this->repo->count(['country_id' => 1]));
        $this->assertEquals(0, $this->repo->count(['country_id' => 99]));
    }

    public function test_exists_returns_bool(): void
    {
        $this->assertTrue($this->repo->exists(['code' => 'CA']));
        $this->assertFalse($this->repo->exists(['code' => 'ZZ']));
    }

    public function test_apply_filter_in_clause(): void
    {
        $result = $this->repo->getAll([
            'code' => ['CA', 'NY'],
        ], paginate: 25);

        $this->assertEquals(2, $result['items']->count());
    }

    public function test_apply_filter_like_operator(): void
    {
        $result = $this->repo->getAll([
            'name' => '%York%',
        ], paginate: 25);

        $this->assertEquals(1, $result['items']->count());
        $this->assertEquals('New York', $result['items']->first()['name']);
    }

    public function test_apply_filter_condition_in(): void
    {
        $result = $this->repo->getAll([
            'code' => ['CONDITION' => 'IN', 'CA', 'NY'],
        ], paginate: 25);

        $this->assertEquals(2, $result['items']->count());
    }

    public function test_apply_filter_condition_not_in(): void
    {
        $result = $this->repo->getAll([
            'code' => ['CONDITION' => 'NOT IN', 'CA'],
        ], paginate: 25);

        $this->assertEquals(2, $result['items']->count());
    }

    public function test_create_then_update_then_delete(): void
    {
        $created = $this->repo->create(['name' => 'Texas', 'code' => 'TX', 'country_id' => 1]);
        $this->assertNotNull($created);
        $this->assertEquals('Texas', $created['name']);

        $updated = $this->repo->update($created['id'], ['name' => 'Texas Updated']);
        $this->assertNotNull($updated);
        $this->assertEquals('Texas Updated', $updated['name']);

        $deleted = $this->repo->delete($created['id']);
        $this->assertTrue($deleted);
        $this->assertNull($this->repo->getByID($created['id']));
    }

    public function test_update_returns_null_for_missing_record(): void
    {
        $this->assertNull($this->repo->update(99999, ['name' => 'X']));
    }

    public function test_delete_returns_false_for_missing_record(): void
    {
        $this->assertFalse($this->repo->delete(99999));
    }

    public function test_map_item_returns_null_when_empty(): void
    {
        $this->assertNull($this->repo->mapItem(null));
    }

    public function test_set_relations_is_chainable(): void
    {
        $returned = $this->repo->setRelations([]);
        $this->assertSame($this->repo, $returned);
    }
}
