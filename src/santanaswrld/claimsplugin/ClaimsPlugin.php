<?php

/**
 *
 * Written by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 *
 * @copyright 2023
 *
 * ClaimsPlugin.php - Main class for ClaimsPlugin
 * This file was refactored by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 */

declare(strict_types=1);

namespace santanaswrld\claimsplugin;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use santanaswrld\claimsplugin\command\ClaimCommand;
use santanaswrld\claimsplugin\data\DataManager;
use santanaswrld\claimsplugin\session\SessionManager;

final class ClaimsPlugin extends PluginBase
{
    /**
     * @var SessionManager
     */
    protected SessionManager $sessionManager;

    /**
     * @var DataManager
     */
    protected DataManager $dataManager;

    use SingletonTrait {
        getInstance as protected getSingletonInstance;
        setInstance as protected;
        reset as protected;
    }

    /**
     * @return void
     */
    protected function onLoad(): void
    {
        ClaimsPlugin::setInstance($this);

        foreach ($this->getResources() as $resource) {
            $this->saveResource($resource->getFilename());
        }
    }

    /**
     * @return ClaimsPlugin
     */
    public static function getInstance(): ClaimsPlugin
    {
        return ClaimsPlugin::getSingletonInstance();
    }

    /**
     * @return void
     */
    protected function onEnable(): void
    {
        $this->sessionManager = new SessionManager($this);
        $this->dataManager = new DataManager($this);

        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        $this->getServer()->getCommandMap()->register("claims", new ClaimCommand($this));
    }

    /**
     * @param string $key
     * @param array $values
     * @return string
     */
    public function getMessage(string $key, array $values = []): string
    {
        $messages = $this->getConfig()->get('messages', []);
        if (!isset($messages[$key])) {
            return "Translation not Found.";
        }

        $messageTemplate = $messages[$key];

        return strtr($messageTemplate, $values);
    }

    /**
     * @return SessionManager
     */
    public function getSessionManager(): SessionManager
    {
        return $this->sessionManager;
    }

    /**
     * @return DataManager
     */
    public function getDataManager(): DataManager
    {
        return $this->dataManager;
    }
}
