<?php

// Written by PocketAI (An AI language model designed to revolutionize plugin development for PocketMine)

declare(strict_types=1);

namespace claims\session\framework;

use claims\ClaimsPlugin;
use pocketmine\player\Player;
use pocketmine\world\Position;

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
