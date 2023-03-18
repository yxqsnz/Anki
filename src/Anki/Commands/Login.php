<?php

namespace Anki\Commands;

use Anki\Data\Manager;
use Anki\Data\PlayerManager;
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
            return $msg->sendErrorMessage($this->manager->fmt("login.missingPasswordArgument"));
        }

        $nick = $sender->getPlayer()->getName();

        if ($this->manager->players->isPlayerAuthenticated($nick)) {
            return $msg->sendErrorMessage($this->manager->fmt("login.alreadyAuthenticated"));
        }

        $password = $args[0];

        if (!$this->manager->players->isPlayerRegistred($nick)) {
            return $msg->sendErrorMessage($this->manager->fmt("login.notRegistred"));
        }

        if ($this->manager->players->login($sender, $password) === true) {
            $msg->sendOkMessage($this->manager->fmt("login.success"));
        } else {
            $msg->sendErrorMessage($this->manager->fmt("login.incorrectPassword"));
        }
    }
}
