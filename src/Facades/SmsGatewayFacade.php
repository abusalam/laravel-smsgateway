<?php
namespace AbuSalam\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AbuSalam\SmsGateway
 */
class SmsGatewayFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SmsGateway::class;
    }
}
