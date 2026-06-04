<?php

namespace Tests\Unit\Integrations;

use App\Services\Notification\SmsService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Transport-level tests for the Twilio SMS integration, exercised through the
 * app-facing SmsService. No database and no real network: every Twilio call is
 * intercepted with Http::fake() and the client's credentials are injected via
 * config() before the service is resolved (TwilioApi reads them in its ctor).
 */
class TwilioSmsTest extends TestCase
{
    private const SID = 'AC0123456789abcdef';
    private const TOKEN = 'secret-auth-token';
    private const FROM = '+15559876543';

    private function configureCredentials(): void
    {
        config()->set('services.twilio.sid', self::SID);
        config()->set('services.twilio.token', self::TOKEN);
        config()->set('services.twilio.from', self::FROM);
    }

    /** Representative Twilio Message resource (subset of real fields). */
    private function fakeMessageBody(): array
    {
        return [
            'sid' => 'SM123',
            'status' => 'queued',
            'to' => '+15551230000',
            'from' => self::FROM,
            'body' => 'Your medical card expires soon.',
            'account_sid' => self::SID,
        ];
    }

    public function test_send_returns_mapped_message_on_success(): void
    {
        $this->configureCredentials();

        Http::fake([
            'api.twilio.com/*' => Http::response($this->fakeMessageBody(), 201),
        ]);

        $service = app(SmsService::class);

        $result = $service->send('+15551230000', 'Your medical card expires soon.');

        $this->assertSame(true, $result['success']);
        $this->assertSame('SM123', $result['sid']);
        $this->assertSame('queued', $result['status']);
        $this->assertSame('+15551230000', $result['to']);
    }

    public function test_send_posts_to_messages_endpoint_with_basic_auth_and_form_body(): void
    {
        $this->configureCredentials();

        Http::fake([
            'api.twilio.com/*' => Http::response($this->fakeMessageBody(), 201),
        ]);

        $service = app(SmsService::class);

        $service->send('+15551230000', 'Your medical card expires soon.');

        Http::assertSent(function ($request) {
            $expectedAuth = 'Basic ' . base64_encode(self::SID . ':' . self::TOKEN);

            return $request->method() === 'POST'
                && str_contains($request->url(), 'Accounts/' . self::SID . '/Messages.json')
                && $request->hasHeader('Authorization', $expectedAuth)
                && $request['To'] === '+15551230000'
                && $request['From'] === self::FROM
                && $request['Body'] === 'Your medical card expires soon.';
        });
    }

    public function test_send_returns_503_error_when_not_configured(): void
    {
        config()->set('services.twilio.sid', '');
        config()->set('services.twilio.token', '');
        config()->set('services.twilio.from', self::FROM);

        Http::fake([
            'api.twilio.com/*' => Http::response($this->fakeMessageBody(), 201),
        ]);

        $service = app(SmsService::class);

        $result = $service->send('+15551230000', 'Hello');

        $this->assertTrue($result['error']);
        $this->assertSame(503, $result['status']);

        Http::assertNothingSent();
    }

    public function test_send_passes_through_error_status_on_400(): void
    {
        $this->configureCredentials();

        Http::fake([
            'api.twilio.com/*' => Http::response([
                'code' => 21211,
                'message' => "The 'To' number is not a valid phone number.",
                'status' => 400,
            ], 400),
        ]);

        $service = app(SmsService::class);

        $result = $service->send('not-a-number', 'Hello');

        $this->assertTrue($result['error']);
        $this->assertSame(400, $result['status']);
        $this->assertArrayNotHasKey('success', $result);
    }
}
