<?php

// Written by PocketAI (An AI language model designed to revolutionize plugin development for PocketMine)

declare(strict_types=1);

namespace claims;

use claims\data\framework\flags\ClaimFlags;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

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

        if($damager instanceof Player && $entity instanceof Player){
            $claim = $this->plugin->getDataManager()->getClaimByPosition($entity->getPosition());
            if($claim !== null && $claim->isFlagActive("nopvp")){
                $damager->sendMessage($this->getPlugin()->getMessage("player.nopvp.attempt"));
                $event->cancel();
            }
        }
    }

    /**
     * @return ClaimsPlugin
     */
    public function getPlugin(): ClaimsPlugin
    {
        return $this->plugin;
    }
}