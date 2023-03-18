<?php

namespace Anki\Utils;

use pocketmine\Player;

class Message
{
    private Player $player;
    public function __construct(
        Player $player
    ) {
        $this->player = $player;
    }

    public function sendErrorMessage(string $message)
    {
        $this->player->sendMessage("§c/!\ Erro! " . $message);
    }

    public function sendOkMessage(string $message)
    {
        $this->player->sendMessage("§a" . $message);
    }
}
