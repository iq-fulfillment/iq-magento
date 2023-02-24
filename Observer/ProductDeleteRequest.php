<?php

namespace IQFulfillment\Magento2Integration\Observer;

use IQFulfillment\Magento2Integration\Helper\IQIntegrationCheck;
use IQFulfillment\Magento2Integration\Helper\IQRequestHandler;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Integration\Model\IntegrationFactory;
use Magento\Store\Model\StoreManagerInterface;

class ProductDeleteRequest implements ObserverInterface
{
    /**
     *
     * @var StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     *
     * @var Curl $curlClient
     */
    protected $curlClient;

    /**
     *
     * @var bool $is_active_integration
     */
    protected $is_active_integration;

    /**
     *
     * @var string $end_point
     */
    private $end_point = "/skus/delete";

    /**
     * @param StoreManagerInterface $storeManager
     * @param IntegrationFactory $integrationFactory
     * @param Curl $curl
     */
    public function __construct(StoreManagerInterface $storeManager, IntegrationFactory $integrationFactory, Curl $curl)
    {
        $this->storeManager = $storeManager;
        $integration_check = new IQIntegrationCheck($integrationFactory);
        $this->is_active_integration = $integration_check->index();
        $this->curlClient = $curl;
    }

    /**
     * Receiving delete product observer event
     *
     * @param Observer $observer
     * @return void
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        if (!$this->is_active_integration) {
            return;
        }
        $product = $observer->getEvent()->getProduct();
        $product_data = $product->getData();
        if ($product_data['type_id'] === "configurable") {
            return;
        }
        $storeUrl = $this->storeManager->getStore()->getBaseUrl();
        $store_currency_code = $this->storeManager->getStore()->getCurrentCurrencyCode();

        $data = [
            "store_url" => $storeUrl,
            "action" => "delete",
            "product_sku" => $product_data['sku'],
            "store_currency_code" => $store_currency_code,
        ];

        $iq_request = new IQRequestHandler($this->curlClient);
        $iq_request->sendRequest($this->end_point, $data);
    }
}
