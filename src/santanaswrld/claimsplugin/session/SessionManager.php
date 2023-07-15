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
 * This file was generated using PocketAI, Branch V7.11.3+dev
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