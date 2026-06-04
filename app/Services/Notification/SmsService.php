<?php

namespace App\Services\Notification;

use App\Mixins\Integrations\AbstractIntegrationApi;
use App\Mixins\Integrations\TwilioApi;

/**
 * App-facing entry point for outbound SMS.
 *
 * Intended to be driven by a scheduled reminder job that scans expiring
 * DriverCdlLicense / DriverMedicalCard / DriverDrugTest records and texts the
 * affected driver/carrier (the job itself is out of scope here). Wraps the
 * Twilio transport and maps its raw Message resource into a clean domain array.
 */
class SmsService
{
    public function __construct(private readonly TwilioApi $api) {}

    /**
     * Send an SMS and return a normalised result.
     *
     * On success: ['success' => true, 'sid' => string, 'status' => string, 'to' => string].
     * On failure: the integration error array is passed straight through.
     */
    public function send(string $to, string $body): array
    {
        $result = $this->api->sendMessage($to, $body);

        if (AbstractIntegrationApi::failed($result)) {
            return $result;
        }

        return $this->mapMessage($result);
    }

    /**
     * Shape a Twilio Message resource into snake_case domain fields.
     */
    private function mapMessage(array $message): array
    {
        return [
            'success' => true,
            'sid' => $message['sid'] ?? null,
            'status' => $message['status'] ?? null,
            'to' => $message['to'] ?? null,
        ];
    }
}
