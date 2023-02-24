<?php
namespace IQFulfillment\Magento2Integration\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Integration\Model\ConfigBasedIntegrationManager;

class InstallPatch implements DataPatchInterface, PatchRevertableInterface
{
    /**
     *
     * @var ConfigBasedIntegrationManager $integrationManager
     */
    private $integrationManager;

    /**
     *
     * @var ModuleDataSetupInterface  $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * interface function
     *
     * @param ConfigBasedIntegrationManager $integrationManager
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ConfigBasedIntegrationManager $integrationManager,
        ModuleDataSetupInterface      $moduleDataSetup
    ) {
        $this->integrationManager = $integrationManager;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * Interface function
     *
     * @return string[]|void
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * Interface function
     *
     * @return string[]|void
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * Interface function
     *
     * @return InstallData|void
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $this->integrationManager->processIntegrationConfig(['IQFulfillmentMagentoIntegration']);
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * Interface function
     *
     * @return void
     */
    public function revert()
    {
        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
