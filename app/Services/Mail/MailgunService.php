<?php

namespace App\Services\Mail;

use App\Contracts\Mail\MailServiceInterface;
use Mailgun\Mailgun;

class MailgunService implements MailServiceInterface
{
    protected $client;
    protected $domain;

    public function __construct()
    {
        $this->client = Mailgun::create(config('services.mailgun.secret'));
        $this->domain = config('services.mailgun.domain');
    }

    public function sendTemplate(
        string $to, 
        string $subject,
        string $template, 
        array $variables
    ) {
        return $this->client->messages()->send($this->domain, [
            'from'                  => 'App <postmaster@' . $this->domain . '>',
            'to'                    => $to,
            'subject'               => $subject,
            'template'              => $template,
            'h:X-Mailgun-Variables' => json_encode($variables),
        ]);
    }
}
