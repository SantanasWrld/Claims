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

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\LeavesDecayEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use santanaswrld\claimsplugin\data\framework\flags\ClaimFlags;

final class EventListener implements Listener
{
    /**
     * @var ClaimsPlugin
     */
    protected ClaimsPlugin $plugin;

    /**
     * @param ClaimsPlugin $plugin
     */
    public function __construct(ClaimsPlugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @param PlayerLoginEvent $event
     * @return void
     */
    public function onPlayerLogin(PlayerLoginEvent $event): void
    {
        $player = $event->getPlayer();
        $this->getPlugin()->getSessionManager()->createSession($player);
    }

    /**
     * @return ClaimsPlugin
     */
    public function getPlugin(): ClaimsPlugin
    {
        return $this->plugin;
    }

    /**
     * @param PlayerQuitEvent $event
     * @return void
     */
    public function onPlayerQuit(PlayerQuitEvent $event): void
    {
        $player = $event->getPlayer();
        $this->getPlugin()->getSessionManager()->removeSession($player->getUniqueId()->getBytes());
    }

    /**
     * @param BlockBreakEvent $event
     * @return void
     */
    public function onBlockBreak(BlockBreakEvent $event): void
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();

        $claim = $this->plugin->getDataManager()->getClaimByPosition($block->getPosition());
        if ($claim !== null && $claim->isFlagActive(ClaimFlags::NO_BREAK)) {
            $player->sendMessage($this->getPlugin()->getMessage("player.nobreak.attempt"));
            $event->cancel();
        }
    }

    /**
     * @param BlockPlaceEvent $event
     * @return void
     */
    public function onBlockPlace(BlockPlaceEvent $event): void
    {
        $player = $event->getPlayer();
        $block = $event->getBlockAgainst();

        $claim = $this->plugin->getDataManager()->getClaimByPosition($block->getPosition());
        if ($claim !== null && $claim->isFlagActive(ClaimFlags::NO_BUILD)) {
            $player->sendMessage($this->getPlugin()->getMessage("player.nobuild.attempt"));
            $event->cancel();
        }
    }

    /**
     * @param EntityDamageByEntityEvent $event
     * @return void
     */
    public function onEntityDamage(EntityDamageByEntityEvent $event): void
    {
        $damager = $event->getDamager();
        $entity = $event->getEntity();

        if ($damager instanceof Player && $entity instanceof Player) {
            $claim = $this->plugin->getDataManager()->getClaimByPosition($entity->getPosition());
            if ($claim !== null && $claim->isFlagActive(ClaimFlags::NO_PVP)) {
                $damager->sendMessage($this->getPlugin()->getMessage("player.nopvp.attempt"));
                $event->cancel();
            }
        }
    }

    /**
     * @param EntityDamageEvent $event
     * @return void
     */
    public function onDamage(EntityDamageEvent $event): void
    {
        $player = $event->getEntity();
        if ($player instanceof Player) {
            $claim = $this->plugin->getDataManager()->getClaimByPosition($player->getPosition());
            if ($claim !== null) {
                if ($claim->isFlagActive(ClaimFlags::NO_DAMAGE)) {
                    $event->cancel();
                } elseif ($claim->isFlagActive(ClaimFlags::NO_FALL) || $event->getCause() === EntityDamageEvent::CAUSE_FALL) {
                    $event->cancel();
                }
            }
        }
    }

    /**
     * @param PlayerExhaustEvent $event
     * @return void
     */
    public function onExhaust(PlayerExhaustEvent $event): void
    {
        $player = $event->getPlayer();
        $claim = $this->plugin->getDataManager()->getClaimByPosition($player->getPosition());
        if ($claim !== null && $claim->isFlagActive(ClaimFlags::NO_STARVE)) {
            $event->cancel();
        }
    }

    /**
     * @param LeavesDecayEvent $event
     * @return void
     */
    public function onDecay(LeavesDecayEvent $event): void
    {
        $claim = $this->plugin->getDataManager()->getClaimByPosition($event->getBlock()->getPosition());
        if ($claim !== null && $claim->isFlagActive(ClaimFlags::NO_DECAY)) {
            $event->cancel();
        }
    }

    /**
     * @param PlayerInteractEvent $event
     * @return void
     */
    public function onItemUse(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();
        $session = $this->plugin->getSessionManager()->getSession($player->getUniqueId()->getBytes());
        if ($player->getInventory()->getItemInHand()->getNamedTag()->getInt("wand", 0) === 1) {
            if ($event->getAction() == PlayerInteractEvent::LEFT_CLICK_BLOCK) {
                $session->setStartingPosition($event->getBlock()->getPosition());
            } elseif ($event->getAction() == PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
                $session->setEndingPosition($event->getBlock()->getPosition());
            }
        }
    }
}