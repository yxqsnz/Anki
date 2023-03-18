<?php

namespace Anki\Commands;

use Anki\Data\Manager;
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

    $password = $args[0];
    $nick = $sender->getPlayer()->getName();

    if ($this->manager->data->playerExists($nick)) {
      return $msg->sendErrorMessage("Você já está registrado!");
    }

    $this->manager->data->addPlayer($nick, $password);
    $msg->sendOkMessage("Você se registrou com sucesso! Bom jogo!");
  }
}
