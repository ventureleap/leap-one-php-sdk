<?php


namespace VentureLeap\LeapOnePhpSdk\Services\ApiProvider;



use VentureLeap\OrderService\Api\OrderApi;
use VentureLeap\OrderService\Api\OrderProductApi;
use VentureLeap\OrderService\Configuration;
use VentureLeap\UserService\Api\ConfigurationEntryApi;

class OrderApiProvider extends AbstractLeapOneApiProvider
{
    const NAME = 'ORDER';

    protected static $CONFIGURATION_CLASS = Configuration::class;

    protected static $CONFIGURATION_ENTRY_API_CLASS = ConfigurationEntryApi::class;

    public function getOrderApi(): OrderApi
    {
        return new OrderApi(null, $this->getConfiguration());
    }

    public function getOrderProductApi(): OrderProductApi
    {
        return new OrderProductApi(null, $this->getConfiguration());
    }
}
