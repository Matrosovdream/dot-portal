<?php

namespace App\Contracts\Mail;

interface MailServiceInterface
{
    public function sendTemplate(
        string $to, 
        string $subject, 
        string $template, 
        array $variables
    );
}
