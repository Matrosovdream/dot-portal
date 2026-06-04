<?php

namespace App\Mixins\Integrations;

use Illuminate\Http\Client\PendingRequest;

/**
 * @scaffold Twilio outbound SMS (Programmable Messaging REST API).
 *   Credentials: services.twilio.sid (Account SID), services.twilio.token
 *   (Auth Token) and services.twilio.from (a Twilio-provisioned sender number).
 *   Compliance: SMS reminders to drivers/carriers require prior express consent
 *   (TCPA) and must honour STOP opt-out keywords; only send to numbers that have
 *   opted in. Degrades to a clean error array when sid/token are absent.
 */
class TwilioApi extends AbstractIntegrationApi
{
    protected string $apiTitle = 'Twilio SMS API';
    protected string $baseUrl = 'https://api.twilio.com/2010-04-01/';

    protected string $sid;
    protected string $token;

    public function __construct()
    {
        $this->sid = (string) config('services.twilio.sid');
        $this->token = (string) config('services.twilio.token');
    }

    /**
     * Twilio authenticates with HTTP Basic (Account SID + Auth Token) and
     * expects form-encoded bodies.
     */
    protected function client(): PendingRequest
    {
        return parent::client()
            ->withBasicAuth($this->sid, $this->token)
            ->asForm();
    }

    protected function isConfigured(): bool
    {
        return $this->sid !== '' && $this->token !== '';
    }

    /**
     * Send a single SMS. Falls back to the configured "from" number when one
     * is not supplied. Returns the decoded Twilio Message resource on success
     * or a normalised error array.
     */
    public function sendMessage(string $to, string $body, ?string $from = null): array
    {
        return $this->post("Accounts/{$this->sid}/Messages.json", [
            'To' => $to,
            'From' => $from ?? config('services.twilio.from'),
            'Body' => $body,
        ]);
    }
}
