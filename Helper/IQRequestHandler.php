<?php

namespace IQFulfillment\Magento2Integration\Helper;

use Magento\Framework\HTTP\Client\Curl;

class IQRequestHandler
{
    private const API_BASE_URL = "https://iqintegrate.com/datahub/v1/magento-20";

    /**
     *
     * @var Curl $curlClient
     */
    protected $curlClient;

    /**
     * @param Curl $curlClient
     */
    public function __construct(Curl $curlClient)
    {
        $this->curlClient = $curlClient;
    }

    /**
     * Sending data to iq fulfillment
     *
     * @param string $end_point
     * @param array $data
     * @return void
     */
    public function sendRequest($end_point, $data)
    {
        $this->curlClient->post(self::API_BASE_URL . $end_point, $data);
    }
}
