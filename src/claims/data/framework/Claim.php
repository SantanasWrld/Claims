<?php

// Written by PocketAI (An AI language model designed to revolutionize plugin development for PocketMine)

declare(strict_types=1);

namespace claims\data\framework;

use pocketmine\world\Position;

class Claim
{
    /**
     * @param string $name
     * @param Position $startingPosition
     * @param Position $endingPosition
     * @param array $flags
     */
    public function __construct(protected string $name, protected Position $startingPosition, protected Position $endingPosition, protected array $flags = [])
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Position
     */
    public function getEndingPosition(): Position
    {
        return $this->endingPosition;
    }

    /**
     * @return Position
     */
    public function getStartingPosition(): Position
    {
        return $this->startingPosition;
    }

    /**
     * @param string $flag
     * @return bool
     */
    public function isFlagActive(string $flag): bool
    {
        return in_array($flag, $this->flags, true);
    }

    /**
     * @return array
     */
    public function getFlags(): array
    {
        return $this->flags;
    }

    /**
     * @param string $flag
     * @return void
     */
    public function removeFlag(string $flag): void
    {
        $key = array_search($flag, $this->flags, true);
        if ($key !== false) {
            unset($this->flags[$key]);
            $this->flags = array_values($this->flags);
        }
    }

    /**
     * @param Claim $other
     * @return bool
     */
    public function doesOverlap(Claim $other): bool
    {
        $minX = min($this->startingPosition->x, $this->endingPosition->x);
        $maxX = max($this->startingPosition->x, $this->endingPosition->x);
        $minZ = min($this->startingPosition->z, $this->endingPosition->z);
        $maxZ = max($this->startingPosition->z, $this->endingPosition->z);

        $otherMinX = min($other->startingPosition->x, $other->endingPosition->x);
        $otherMaxX = max($other->startingPosition->x, $other->endingPosition->x);
        $otherMinZ = min($other->startingPosition->z, $other->endingPosition->z);
        $otherMaxZ = max($other->startingPosition->z, $other->endingPosition->z);

        return !($otherMaxX < $minX || $otherMinX > $maxX || $otherMaxZ < $minZ || $otherMinZ > $maxZ);
    }

    /**
     * @param string $flag
     * @return void
     */
    public function addFlag(string $flag): void
    {
        if (!in_array($flag, $this->flags)) {
            $this->flags[] = $flag;
        }
    }
}