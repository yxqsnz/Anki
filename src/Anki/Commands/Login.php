<?php

namespace Anki\Commands;

use Anki\Data\Manager;
use Anki\Utils\Message;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Login extends Command
{
    private Manager $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
        parent::__construct("login", "Loga no servidor", "/login <SENHA>", ["logar"]);
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
        $dbPlayer = $this->manager->data->getPlayer($nick);

        if ($dbPlayer === null) {
            return $msg->sendErrorMessage("Você não está registrado!");
        }

        if (!password_verify($password, $dbPlayer->password)) {
            return $msg->sendErrorMessage("Senha incorreta!");
        }

        $msg->sendOkMessage("Você logou com sucesso! Bom jogo!");
    }
}
