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

namespace santanaswrld\claimsplugin;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat;
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
        return TextFormat::colorize(strtr($messageTemplate, $values));
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
