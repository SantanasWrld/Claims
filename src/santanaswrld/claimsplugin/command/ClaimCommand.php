<?php

// Written by PocketAI (An AI language model designed to revolutionize plugin development for PocketMine)

declare(strict_types=1);

namespace santanaswrld\claimsplugin\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\PluginOwnedTrait;
use pocketmine\utils\TextFormat;
use santanaswrld\claimsplugin\ClaimsPlugin;
use santanaswrld\claimsplugin\command\form\SimpleForm;
use santanaswrld\claimsplugin\data\framework\Claim;
use santanaswrld\claimsplugin\data\framework\flags\ClaimFlags;

final class ClaimCommand extends Command implements PluginOwned
{
    use PluginOwnedTrait;

    /**
     * @param ClaimsPlugin $plugin
     */
    public function __construct(protected ClaimsPlugin $plugin)
    {
        parent::__construct("claim", "Claim Root Command", null, ["cl"]);
        $this->setPermission("claims.administrator");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        if (count($args) < 1) {
            $sender->sendMessage($this->getOwningPlugin()->getMessage("command.invalid.input"));
            return;
        }

        $session = $this->plugin->getSessionManager()->getSession($sender->getUniqueId()->getBytes());
        switch (strtolower($args[0])) {
            case "pos1":
            case "1":
            case "position1":
            case "start":
                $this->plugin->getSessionManager()->getSession($sender->getUniqueId()->getBytes())->setStartingPosition($sender->getPosition());
                $sender->sendMessage($this->getOwningPlugin()->getMessage("command.position1.success"));
                break;

            case "pos2":
            case "2":
            case "position2":
            case "end":
                $this->plugin->getSessionManager()->getSession($sender->getUniqueId()->getBytes())->setEndingPosition($sender->getPosition());
                $sender->sendMessage($this->getOwningPlugin()->getMessage("command.position2.success"));
                break;

            case "create":
                if (count($args) < 2) {
                    $sender->sendMessage($this->getOwningPlugin()->getMessage("command.missing.name"));
                    return;
                }

                if ($session->getStartingPosition() === null || $session->getEndingPosition() === null) {
                    $sender->sendMessage($this->getOwningPlugin()->getMessage("command.missing.position"));
                    return;
                }

                $claimName = $args[1];
                if ($this->getOwningPlugin()->getDataManager()->getClaimByName($claimName) !== null) {
                    $claimName = $args[1] . mt_rand(1, 999999);
                }

                $claim = new Claim($claimName, $session->getStartingPosition(), $session->getEndingPosition());
                if ($this->getOwningPlugin()->getDataManager()->doesOverlapWithAny($claim)) {
                    $sender->sendMessage($this->getOwningPlugin()->getMessage("command.claims.overlap"));
                    return;
                }

                $this->plugin->getDataManager()->addClaim($claim);
                $session->setStartingPosition(null);
                $session->setEndingPosition(null);
                $sender->sendMessage($this->getOwningPlugin()->getMessage("command.claim.create", ["{claim}" => $claimName]));
                break;

            case "editflags":
                $this->openFlagsForm($sender);
                break;

            case "here":
            case "insideof":
            case "inside":
                $claim = $this->plugin->getDataManager()->getClaimByPosition($sender->getPosition());
                if ($claim === null) {
                    $sender->sendMessage($this->getOwningPlugin()->getMessage("command.outside.claim"));
                    return;
                }

                $sender->sendMessage($this->getOwningPlugin()->getMessage("command.insideof.success", ["{claim}" => $claim->getName()]));
                break;

            case "tp":
            case "teleport":
                if (count($args) < 2) {
                    $sender->sendMessage($this->getOwningPlugin()->getMessage("command.missing.name"));
                    return;
                }

                $claim = $this->getOwningPlugin()->getDataManager()->getClaimByName($args[1]);
                if ($claim !== null) {
                    $position = $claim->getStartingPosition();
                    $sender->teleport($position);
                    $sender->sendMessage($this->getOwningPlugin()->getMessage("command.teleport.success", ["{claim}" => $claim->getName()]));
                } else {
                    $sender->sendMessage($this->getOwningPlugin()->getMessage("command.claim.failed"));
                }
                break;

            case "list":
                $claims = $this->getOwningPlugin()->getDataManager()->getClaims();
                if (count($claims) > 0) {
                    $sender->sendMessage($this->getOwningPlugin()->getMessage("command.list.header"));
                    foreach ($claims as $claim) {
                        $sender->sendMessage($this->getOwningPlugin()->getMessage("command.list.format", [
                            "{name}" => $claim->getName(),
                            "{x1}" => $claim->getStartingPosition()->getX(),
                            "{y1}" => $claim->getStartingPosition()->getY(),
                            "{z1}" => $claim->getStartingPosition()->getZ(),
                            "{world}" => $claim->getStartingPosition()->getWorld()->getFolderName(),
                            "{x2}" => $claim->getEndingPosition()->getX(),
                            "{y2}" => $claim->getEndingPosition()->getY(),
                            "{z2}" => $claim->getEndingPosition()->getZ()
                        ]));
                    }
                } else {
                    $sender->sendMessage($this->getOwningPlugin()->getMessage("command.claim.failed"));
                }
                break;

            case "help":
            default:
                $sender->sendMessage($this->getOwningPlugin()->getMessage("command.invalid.input"));
                break;
        }
    }

    /**
     * @param Player $player
     * @return void
     */
    protected function openFlagsForm(Player $player): void
    {
        $form = new SimpleForm(function (Player $player, int $data = null) use (&$flags, &$claim) {
            if ($data === null) {
                return;
            }

            $flag = $flags[$data];
            if ($claim !== null && $claim->isFlagActive($flag)) {
                $claim->removeFlag($flag);
                $this->plugin->getDataManager()->saveClaim($claim);
                $player->sendMessage($this->getOwningPlugin()->getMessage("command.flag.added", ["{flag}" => $flag]));
            } else {
                $claim->addFlag($flag);
                $player->sendMessage($this->getOwningPlugin()->getMessage("command.flag.removed", ["{flag}" => $flag]));
            }
        });

        $form->setTitle("Edit Claim Flags");
        $form->setContent("Choose the flags you want to set:");

        $claim = $this->plugin->getDataManager()->getClaimByPosition($player->getPosition());
        if ($claim === null) {
            $player->sendMessage($this->getOwningPlugin()->getMessage("command.outside.claim"));
            return;
        }

        $flags = [ClaimFlags::NO_BUILD, ClaimFlags::NO_BREAK, ClaimFlags::NO_PVP, ClaimFlags::NO_DAMAGE];
        foreach ($flags as $flag) {
            $active = $claim->isFlagActive($flag);
            $form->addButton(($active ? TextFormat::GREEN : TextFormat::RED) . ucfirst($flag));
        }

        $player->sendForm($form);
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin
    {
        return $this->plugin;
    }
}