<?php

/**
 * `7MM"""Mq.                 `7MM              mm        db     `7MMF'
 *   MM   `MM.                  MM              MM       ;MM:      MM
 *   MM   ,M9 ,pW"Wq.   ,p6"bo  MM  ,MP.gP"Ya mmMMmm    ,V^MM.     MM
 *   MMmmdM9 6W'   `Wb 6M'  OO  MM ;Y ,M'   Yb  MM     ,M  `MM     MM
 *   MM      8M     M8 8M       MM;Mm 8M""""""  MM     AbmmmqMA    MM
 *   MM      YA.   ,A9 YM.    , MM `MbYM.    ,  MM    A'     VML   MM
 * .JMML.     `Ybmd9'   YMbmd'.JMML. YA`Mbmmd'  `Mbm.AMA.   .AMMA.JMML.
 *
 * This file was generated using PocketAI, Branch V7.12.4+dev
 *
 * PocketAI is private software: You can redistribute the files under
 * the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at your option)
 * any later version.
 *
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this file. If not, see <http://www.gnu.org/licenses/>.
 *
 * @ai-profile SantanasWrld
 * @copyright 2023
 * @authors NopeNotDark, SantanasWrld
 * @link https://thedarkproject.net/pocketai
 */

declare(strict_types=1);

namespace santanaswrld\claimsplugin\data\framework;

use pocketmine\world\Position;

class Claim
{
    /**
     * @param string $name
     * @param Position $startingPosition
     * @param Position $endingPosition
     * @param array $flags
     */
    public function __construct(
        protected string   $name,
        protected Position $startingPosition,
        protected Position $endingPosition,
        protected array    $flags = []
    )
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