<?php

namespace Anki\Commands;

use Anki\Utils\Message;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Register extends Command
{
  public function __construct()
  {
    parent::__construct("register", "Registra uma conta", "/register <SENHA>", ["registrar"]);
  }

  public function execute(CommandSender $sender, $commandLabel, array $args)
  {
    $msg = new Message($sender);

    if (!$sender instanceof Player) {
      return;
    }

    if (!isset($args[0])) {
      return $msg->sendErrorMessage("Você precisa botar uma senha como argumento!");
    }
  }
}
