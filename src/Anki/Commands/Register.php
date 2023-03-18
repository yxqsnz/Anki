<?php

namespace Anki\Commands;

use Anki\Data\Manager;
use Anki\Data\PlayerManager;
use Anki\Utils\Message;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Register extends Command
{
  private Manager $manager;

  public function __construct(Manager $manager)
  {
    $this->manager = $manager;
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

    $nick = $sender->getPlayer()->getName();

    if ($this->manager->players->isPlayerAuthenticated($nick)) {
      return $msg->sendErrorMessage($this->manager->fmt("register.alreadyAuthenticated"));
    }

    $password = $args[0];

    if ($this->manager->players->isPlayerRegistred($nick)) {
      return $msg->sendErrorMessage($this->manager->fmt("register.alreadyRegistred"));
    }

    $this->manager->players->register($sender, $password);
    $msg->sendOkMessage($this->manager->fmt("register.success"));
  }
}
