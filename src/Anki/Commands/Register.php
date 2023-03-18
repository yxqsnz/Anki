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
  private PlayerManager $manager;

  public function __construct(Manager $manager)
  {
    $this->manager = $manager->players;
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

    if ($this->manager->isPlayerAuthenticated($nick)) {
      return $msg->sendErrorMessage("Você já está autenticado.");
    }

    $password = $args[0];

    if ($this->manager->isPlayerRegistred($nick)) {
      return $msg->sendErrorMessage("Você já está registrado!");
    }

    $this->manager->register($nick, $password);
    $msg->sendOkMessage("Você se registrou com sucesso! Bom jogo!");
  }
}
