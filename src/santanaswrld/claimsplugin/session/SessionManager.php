<?php

/**
 *
 * Written by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 *
 * @copyright 2023
 *
 * SessionManager.php - Main class for session manager
 * This file was refactored by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 */

declare(strict_types=1);

namespace santanaswrld\claimsplugin\session;

use pocketmine\player\Player;
use santanaswrld\claimsplugin\ClaimsPlugin;
use santanaswrld\claimsplugin\session\framework\Session;

final class SessionManager
{
    /**
     * @var ClaimsPlugin
     */
    protected ClaimsPlugin $plugin;

    /**
     * @var Session[]
     */
    protected array $sessions = [];

    /**
     * @param ClaimsPlugin $plugin
     */
    public function __construct(ClaimsPlugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @return ClaimsPlugin
     */
    public function getPlugin(): ClaimsPlugin
    {
        return $this->plugin;
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function createSession(Player $player): bool
    {
        if ($this->hasSession($player->getUniqueId()->getBytes())) {
            return false;
        }

        $this->sessions[$player->getUniqueId()->getBytes()] = new Session($this->getPlugin(), $player);
        return true;
    }

    /**
     * @param string $uid
     * @return bool
     */
    public function hasSession(string $uid): bool
    {
        return isset($this->sessions[$uid]);
    }

    /**
     * @param string $uid
     * @return bool
     */
    public function removeSession(string $uid): bool
    {
        if (!$this->hasSession($uid)) {
            return false;
        }

        unset($this->sessions[$uid]);
        return true;
    }

    /**
     * @param string $uid
     * @return Session|null
     */
    public function getSession(string $uid): ?Session
    {
        return $this->sessions[$uid] ?? null;
    }

    /**
     * @return array
     */
    public function getSessions(): array
    {
        return $this->sessions;
    }
}