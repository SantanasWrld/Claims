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
    public function __construct(
        protected ClaimsPlugin $plugin,
        protected Player $player
    )
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
