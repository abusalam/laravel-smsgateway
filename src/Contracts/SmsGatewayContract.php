<?php
namespace AbuSalam\Contracts;

interface SmsGatewayContract
{
    const VERSION = '0.0.1';

    /**
     * Send an SMS
     *
     */
    public function sendSms();
    
    /**
     * Configure SMS Gateway
     *
     */
    public function fromGateway($gateway);

    /**
     * Create SMS Payload
     *
     */
    public function withSms($payload);

    /**
     * Receipents for Group SMS
     *
     */
    public function toRecipient($toMobile);
}
