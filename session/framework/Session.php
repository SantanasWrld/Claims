<?php

/**
 *
 * Written by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 *
 * @copyright 2023
 *
 * Session.php - Main class for session
 * This file was refactored by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 */

declare(strict_types=1);

namespace santanaswrld\claimsplugin\session\framework;

use pocketmine\player\Player;
use pocketmine\world\Position;
use santanaswrld\claimsplugin\ClaimsPlugin;

final class Session
{
    /**
     * @var Position|null
     */
    protected ?Position $startingPosition = null;

    /**
     * @var Position|null
     */
    protected ?Position $endingPosition = null;

    /**
     * @param ClaimsPlugin $plugin
     * @param Player $player
     */
    public function __construct(protected ClaimsPlugin $plugin, protected Player $player)
    {
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return ClaimsPlugin
     */
    public function getPlugin(): ClaimsPlugin
    {
        return $this->plugin;
    }

    /**
     * @return Position
     */
    public function getStartingPosition(): Position
    {
        return $this->startingPosition;
    }

    /**
     * @return Position
     */
    public function getEndingPosition(): Position
    {
        return $this->endingPosition;
    }

    /**
     * @param Position|null $startingPosition
     * @return void
     */
    public function setStartingPosition(?Position $startingPosition): void
    {
        $this->startingPosition = $startingPosition;
    }

    /**
     * @param Position|null $endingPosition
     * @return void
     */
    public function setEndingPosition(?Position $endingPosition): void
    {
        $this->endingPosition = $endingPosition;
    }
}
