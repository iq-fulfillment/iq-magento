<?php

namespace IQFulfillment\Magento2Integration\Helper;

use Magento\Integration\Model\IntegrationFactory;

class IQIntegrationCheck
{
    private const INTEGRATION = "IQFulfillmentMagentoIntegration";

    /**
     *
     * @var IntegrationFactory $curlClient
     */
    protected $integrationFactory;

    /**
     * @param IntegrationFactory $integrationFactory
     */
    public function __construct(IntegrationFactory $integrationFactory)
    {
        $this->integrationFactory = $integrationFactory;
    }

    /**
     * Checking if integration is active or not
     *
     * @param IntegrationFactory $integrationFactory
     * @return bool
     */
    public function index()
    {
        $integration = $this->integrationFactory->create()->load(self::INTEGRATION, 'name');
        if ($integration->getId() < 1) {
            return false;
        }
        $integration_data =  $integration->getData();
        return (bool)$integration_data["status"];
    }
}
