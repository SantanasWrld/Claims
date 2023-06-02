<?php

// Written by PocketAI (An AI language model designed to revolutionize plugin development for PocketMine)

declare(strict_types=1);

namespace claims\command\form;

use pocketmine\form\Form as IForm;
use pocketmine\player\Player;

abstract class Form implements IForm
{
    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @var callable|null
     */
    private $callable;

    /**
     * @param callable|null $callable
     */
    public function __construct(?callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @param Player $player
     * @see Player::sendForm()
     *
     * @deprecated
     */
    public function sendToPlayer(Player $player): void
    {
        $player->sendForm($this);
    }

    /**
     * @param Player $player
     * @param $data
     * @return void
     */
    public function handleResponse(Player $player, $data): void
    {
        $this->processData($data);
        $callable = $this->getCallable();
        if ($callable !== null) {
            $callable($player, $data);
        }
    }

    /**
     * @param $data
     * @return void
     */
    public function processData(&$data): void
    {
    }

    /**
     * @return callable|null
     */
    public function getCallable(): ?callable
    {
        return $this->callable;
    }

    /**
     * @param callable|null $callable
     * @return void
     */
    public function setCallable(?callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->data;
    }
}