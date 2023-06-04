<?php

/**
 *
 * Written by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 *
 * @copyright 2023
 *
 * DataManager.php - Main class for data manager
 * This file was refactored by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 */

declare(strict_types=1);

namespace santanaswrld\claimsplugin\data;

use pocketmine\math\Vector3;
use pocketmine\world\Position;
use santanaswrld\claimsplugin\ClaimsPlugin;
use santanaswrld\claimsplugin\data\framework\Claim;

final class DataManager
{
    /**
     * @var ClaimsPlugin
     */
    protected ClaimsPlugin $plugin;

    /**
     * @var Claim[]
     */
    protected array $claims = [];

    /**
     * @param ClaimsPlugin $plugin
     */
    public function __construct(ClaimsPlugin $plugin)
    {
        $this->plugin = $plugin;
        if (file_exists($filePath = ($plugin->getDataFolder() . "claims.json"))) {
            $jsonData = file_get_contents($filePath);
            $data = json_decode($jsonData, true);
            foreach ($data as $claimData) {
                $name = strval($claimData['name']);
                $startingPositionArray = $claimData['startingPosition'];
                $startingPositionVector = new Vector3($startingPositionArray['x'], $startingPositionArray['y'], $startingPositionArray['z']);
                $startingPositionWorld = $this->plugin->getServer()->getWorldManager()->getWorldByName($startingPositionArray['world']);
                $startingPosition = Position::fromObject($startingPositionVector, $startingPositionWorld);
                $endingPositionArray = $claimData['endingPosition'];
                $endingPositionVector = new Vector3($endingPositionArray['x'], $endingPositionArray['y'], $endingPositionArray['z']);
                $endingPositionWorld = $this->plugin->getServer()->getWorldManager()->getWorldByName($endingPositionArray['world']);
                $endingPosition = Position::fromObject($endingPositionVector, $endingPositionWorld);
                $flags = $claimData['flags'];
                $this->claims[] = new Claim($name, $startingPosition, $endingPosition, $flags);
            }
        }
    }

    /**
     * @param Position $position
     * @return Claim|null
     */
    public function getClaimByPosition(Position $position): ?Claim
    {
        foreach ($this->claims as $claim) {
            $startingPosition = $claim->getStartingPosition();
            $endingPosition = $claim->getEndingPosition();

            if ($startingPosition->getWorld()->getFolderName() !== $position->getWorld()->getFolderName()) {
                continue;
            }

            $minX = min($startingPosition->x, $endingPosition->x);
            $maxX = max($startingPosition->x, $endingPosition->x);
            $minY = min($startingPosition->y, $endingPosition->y);
            $maxY = max($startingPosition->y, $endingPosition->y);
            $minZ = min($startingPosition->z, $endingPosition->z);
            $maxZ = max($startingPosition->z, $endingPosition->z);

            if ($position->x >= $minX && $position->x <= $maxX && $position->y >= $minY && $position->y <= $maxY && $position->z >= $minZ && $position->z <= $maxZ) {
                return $claim;
            }
        }
        return null;
    }

    /**
     * @param Claim $claim
     * @return void
     */
    public function addClaim(Claim $claim): void
    {
        $this->claims[] = $claim;
        $this->saveClaims();
    }

    /**
     * @param Claim $claim
     * @return void
     */
    public function removeClaim(Claim $claim): void
    {
        foreach ($this->claims as $key => $claimA) {
            if ($claimA === $claim) {
                unset($this->claims[$key]);
                $this->saveClaims();
                break;
            }
        }
    }

    /**
     * @return void
     */
    public function saveClaims(): void
    {
        $data = [];
        $this->claims = array_values($this->claims);
        foreach ($this->claims as $claim) {
            $data[] = [
                'name' => $claim->getName(),
                'startingPosition' => ['x' => $claim->getStartingPosition()->getX(), 'y' => $claim->getStartingPosition()->getY(), 'z' => $claim->getStartingPosition()->getZ(), 'world' => $claim->getStartingPosition()->getWorld()->getFolderName()],
                'endingPosition' => ['x' => $claim->getEndingPosition()->getX(), 'y' => $claim->getEndingPosition()->getY(), 'z' => $claim->getEndingPosition()->getZ(), 'world' => $claim->getEndingPosition()->getWorld()->getFolderName()],
                'flags' => $claim->getFlags(),
            ];
        }
        file_put_contents($this->getPlugin()->getDataFolder() . "claims.json", json_encode($data));
    }

    /**
     * @param Claim $claim
     * @return void
     */
    public function saveClaim(Claim $claim): void
    {
        $claimData = [
            'name' => $claim->getName(),
            'startingPosition' => [
                'x' => $claim->getStartingPosition()->getX(),
                'y' => $claim->getStartingPosition()->getY(),
                'z' => $claim->getStartingPosition()->getZ(),
                'world' => $claim->getStartingPosition()->getWorld()->getFolderName(),
            ],
            'endingPosition' => [
                'x' => $claim->getEndingPosition()->getX(),
                'y' => $claim->getEndingPosition()->getY(),
                'z' => $claim->getEndingPosition()->getZ(),
                'world' => $claim->getEndingPosition()->getWorld()->getFolderName(),
            ],
            'flags' => $claim->getFlags(),
        ];

        $existingClaims = json_decode(file_get_contents($dataPath = ($this->plugin->getDataFolder() . "claims.json")), true);
        $existingClaims[$claim->getName()] = $claimData;
        file_put_contents($dataPath, json_encode($existingClaims, JSON_PRETTY_PRINT));
    }

    /**
     * @param string $claimName
     * @return Claim|null
     */
    public function getClaimByName(string $claimName): ?Claim
    {
        $claimFile = $this->plugin->getDataFolder() . "claims.json";
        if (!file_exists($claimFile)) {
            return null;
        }

        $claimsData = json_decode(file_get_contents($claimFile), true);
        if (!isset($claimsData[$claimName])) {
            return null;
        }

        $claimData = $claimsData[$claimName];

        $startingPosition = new Position(
            $claimData['startingPosition']['x'],
            $claimData['startingPosition']['y'],
            $claimData['startingPosition']['z'],
            $this->plugin->getServer()->getWorldManager()->getWorldByName($claimData['startingPosition']['world'])
        );

        $endingPosition = new Position(
            $claimData['endingPosition']['x'],
            $claimData['endingPosition']['y'],
            $claimData['endingPosition']['z'],
            $this->plugin->getServer()->getWorldManager()->getWorldByName($claimData['endingPosition']['world'])
        );

        $flags = $claimData['flags'];
        return new Claim($claimName, $startingPosition, $endingPosition, $flags);
    }

    /**
     * @return ClaimsPlugin
     */
    public function getPlugin(): ClaimsPlugin
    {
        return $this->plugin;
    }

    /**
     * @param Claim $claim
     * @return bool
     */
    public function doesOverlapWithAny(Claim $claim): bool
    {
        foreach ($this->getClaims() as $existingClaim) {
            if ($claim->doesOverlap($existingClaim)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public function getClaims(): array
    {
        return $this->claims;
    }
}