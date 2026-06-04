<?php

namespace Tests\Unit\Integrations;

use App\Services\Driver\ClearinghouseService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Unit coverage for the FMCSA Clearinghouse integration. Exercises
 * App\Services\Driver\ClearinghouseService over App\Mixins\Integrations\ClearinghouseApi
 * using Http::fake() only — no real network calls, no database.
 *
 * The client reads its credentials in the constructor, so every test configures
 * services.clearinghouse.* BEFORE resolving the service via app().
 */
class ClearinghouseServiceTest extends TestCase
{
    private const BASE_URL = 'https://ctpa-partner.example.com/api';

    private const FAKE_GLOB = 'ctpa-partner.example.com/*';

    private function configure(string $baseUrl = self::BASE_URL, string $apiKey = 'secret-token'): void
    {
        config()->set('services.clearinghouse.base_url', $baseUrl);
        config()->set('services.clearinghouse.api_key', $apiKey);
    }

    private function driver(): array
    {
        return [
            'first_name' => 'JOHN',
            'last_name' => 'DOE',
            'dob' => '1980-05-04',
            'license_number' => 'D1234567',
            'license_state' => 'TX',
        ];
    }

    public function test_query_success_maps_envelope_and_posts_to_queries_with_bearer(): void
    {
        $this->configure();

        Http::fake([
            self::FAKE_GLOB => Http::response(['query_id' => 'q1', 'status' => 'pending'], 200),
        ]);

        $service = app(ClearinghouseService::class);

        $result = $service->query($this->driver(), 'limited');

        $this->assertTrue($result['success']);
        $this->assertSame('q1', $result['query_id']);
        $this->assertSame('pending', $result['status']);
        $this->assertArrayNotHasKey('error', $result);

        Http::assertSent(function ($request) {
            return $request->method() === 'POST'
                && str_contains($request->url(), 'queries')
                && $request->hasHeader('Authorization', 'Bearer secret-token')
                && $request['type'] === 'limited'
                && $request['consent'] === true
                && $request['driver']['first_name'] === 'JOHN'
                && $request['license']['number'] === 'D1234567';
        });
    }

    public function test_query_defaults_invalid_type_to_limited(): void
    {
        $this->configure();

        Http::fake([
            self::FAKE_GLOB => Http::response(['query_id' => 'q1', 'status' => 'pending'], 200),
        ]);

        $service = app(ClearinghouseService::class);

        $service->query($this->driver(), 'bogus-type');

        Http::assertSent(function ($request) {
            return $request['type'] === 'limited';
        });
    }

    public function test_query_passes_through_full_type(): void
    {
        $this->configure();

        Http::fake([
            self::FAKE_GLOB => Http::response(['query_id' => 'q2', 'status' => 'pending'], 200),
        ]);

        $service = app(ClearinghouseService::class);

        $service->query($this->driver(), 'full');

        Http::assertSent(function ($request) {
            return $request['type'] === 'full'
                && $request['consent'] === true;
        });
    }

    public function test_query_defaults_to_limited_when_type_omitted(): void
    {
        $this->configure();

        Http::fake([
            self::FAKE_GLOB => Http::response(['query_id' => 'q3', 'status' => 'pending'], 200),
        ]);

        $service = app(ClearinghouseService::class);

        $result = $service->query($this->driver());

        $this->assertTrue($result['success']);

        Http::assertSent(function ($request) {
            return $request['type'] === 'limited';
        });
    }

    public function test_query_status_defaults_to_pending_when_absent(): void
    {
        $this->configure();

        Http::fake([
            self::FAKE_GLOB => Http::response(['query_id' => 'q4'], 200),
        ]);

        $service = app(ClearinghouseService::class);

        $result = $service->query($this->driver());

        $this->assertTrue($result['success']);
        $this->assertSame('q4', $result['query_id']);
        $this->assertSame('pending', $result['status']);
    }

    public function test_result_success_maps_violations_and_gets_query_by_id_with_bearer(): void
    {
        $this->configure();

        $body = [
            'status' => 'complete',
            'queried_at' => '2025-01-01',
            'violations' => [
                [
                    'test_date' => '2024-11-12',
                    'test_type' => 'pre-employment',
                    'result' => 'positive',
                    'description' => 'Positive drug test',
                ],
            ],
        ];

        Http::fake([
            self::FAKE_GLOB => Http::response($body, 200),
        ]);

        $service = app(ClearinghouseService::class);

        $result = $service->result('q1');

        $this->assertSame('complete', $result['status']);
        $this->assertSame('2025-01-01', $result['queried_at']);
        $this->assertCount(1, $result['violations']);

        $violation = $result['violations'][0];
        $this->assertSame('2024-11-12', $violation['test_date']);
        $this->assertSame('pre-employment', $violation['test_type']);
        $this->assertSame('positive', $violation['result']);
        $this->assertSame('Positive drug test', $violation['description']);

        $this->assertSame($body, $result['raw']);

        Http::assertSent(function ($request) {
            return $request->method() === 'GET'
                && str_contains($request->url(), 'queries/q1')
                && $request->hasHeader('Authorization', 'Bearer secret-token');
        });
    }

    public function test_result_maps_alternate_violation_field_names(): void
    {
        $this->configure();

        $body = [
            'status' => 'complete',
            'completed_at' => '2025-02-02',
            'violations' => [
                [
                    'violation_date' => '2024-09-01',
                    'type' => 'random',
                    'status' => 'refusal',
                    'desc' => 'Refused to test',
                ],
            ],
        ];

        Http::fake([
            self::FAKE_GLOB => Http::response($body, 200),
        ]);

        $service = app(ClearinghouseService::class);

        $result = $service->result('q9');

        $this->assertSame('2025-02-02', $result['queried_at']);

        $violation = $result['violations'][0];
        $this->assertSame('2024-09-01', $violation['test_date']);
        $this->assertSame('random', $violation['test_type']);
        $this->assertSame('refusal', $violation['result']);
        $this->assertSame('Refused to test', $violation['description']);
    }

    public function test_query_returns_503_when_not_configured(): void
    {
        $this->configure(baseUrl: '', apiKey: '');

        Http::fake([
            self::FAKE_GLOB => Http::response(['query_id' => 'q1', 'status' => 'pending'], 200),
        ]);

        $service = app(ClearinghouseService::class);

        $result = $service->query($this->driver());

        $this->assertTrue($result['error']);
        $this->assertSame(503, $result['status']);

        Http::assertNothingSent();
    }

    public function test_result_returns_503_when_not_configured(): void
    {
        $this->configure(apiKey: '');

        Http::fake([
            self::FAKE_GLOB => Http::response(['status' => 'complete', 'violations' => []], 200),
        ]);

        $service = app(ClearinghouseService::class);

        $result = $service->result('q1');

        $this->assertTrue($result['error']);
        $this->assertSame(503, $result['status']);

        Http::assertNothingSent();
    }

    public function test_query_passes_through_error_status(): void
    {
        $this->configure();

        Http::fake([
            self::FAKE_GLOB => Http::response(['message' => 'Bad request'], 422),
        ]);

        $service = app(ClearinghouseService::class);

        $result = $service->query($this->driver());

        $this->assertTrue($result['error']);
        $this->assertSame(422, $result['status']);
    }

    public function test_result_passes_through_error_status(): void
    {
        $this->configure();

        Http::fake([
            self::FAKE_GLOB => Http::response(['message' => 'Server exploded'], 500),
        ]);

        $service = app(ClearinghouseService::class);

        $result = $service->result('q1');

        $this->assertTrue($result['error']);
        $this->assertSame(500, $result['status']);
    }
}
