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

class OrderCancelRequest implements ObserverInterface
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
    private $end_point = "/orders/cancel";

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
     * Receiving cancel order observer event
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
        $storeUrl = $this->storeManager->getStore()->getBaseUrl();
        $order = $observer->getEvent()->getOrder();
        $order_data = $order->getData();

        $data = [
            "store_url" => $storeUrl,
            "action" => "cancel",
            "order_id" => $order_data['entity_id'],
            "increment_id" => $order_data['increment_id']
        ];
        $iq_request = new IQRequestHandler($this->curlClient);
        $iq_request->sendRequest($this->end_point, $data);
    }
}
