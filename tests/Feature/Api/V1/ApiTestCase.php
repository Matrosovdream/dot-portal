<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;

abstract class ApiTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Make Sanctum's EnsureFrontendRequestsAreStateful::fromFrontend()
        // accept the test request (its Referer must start with the host).
        $this->withHeaders([
            'Referer' => 'http://localhost',
        ]);
    }
}
