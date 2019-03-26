<?php

namespace AlexBrin\Qiwi;

use AlexBrin\Qiwi\Command\DonateCommand;
use AlexBrin\Qiwi\Donate\DonateManager;
use AlexBrin\Qiwi\Purchase\PurchaseManager;
use AlexBrin\Qiwi\Task\PurchaseUpdateAsyncTask;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var PurchaseManager
     */
    private $purchaseManager;

    /**
     * @var DonateManager
     */
    private $donateManager;

    /**
     * @var MessageManager
     */
    private $messageManager;

    /**
     * @var Qiwi
     */
    private $qiwi;

    private static $instance;

    public function onEnable()
    {
        $f = $this->getDataFolder();
        if(!is_dir($f))
            @mkdir($f);

        $this->saveResource('config.yml');
        $this->saveResource('donate.yml');
        $this->saveResource('message.yml');
        $this->config = new Config($f . 'config.yml', Config::YAML);
        $this->purchaseManager = new PurchaseManager($f);
        $this->donateManager = new DonateManager($f);
        $this->messageManager = new MessageManager($f);

        $this->getServer()
            ->getCommandMap()
            ->register('aqiwidonate', new DonateCommand(
            'donate',
            'Донат-меню',
            '/donate'
        ));

        self::$instance =& $this;
    }

    /**
     * @return PurchaseManager
     */
    public function getPurchaseManager(): PurchaseManager {
        return $this->purchaseManager;
    }

    /**
     * @return DonateManager
     */
    public function getDonateManager(): DonateManager {
        return $this->donateManager;
    }

    /**
     * @return MessageManager
     */
    public function getMessageManager(): MessageManager {
        return $this->messageManager;
    }

    /**
     * @return Qiwi
     */
    public function getQiwiApi(): Qiwi {
        return $this->qiwi;
    }

    /**
     * @return Main
     */
    public static function getInstance(): Main {
        return self::$instance;
    }

}