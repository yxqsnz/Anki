<?php

namespace Anki;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\math\Vector3;
use pocketmine\Player;

class ANKLogin extends Command
{
  public function __construct()
  {
    parent::__construct("login", "owo", "/login", ["log"]);
  }

  public function execute(CommandSender $sender, $commandLabel, array $args)
  {


    if (!$sender instanceof Player) {
      return;
    }

    if (!isset($args[0])) {
      $sender->sendMessage("a");
      return;
    }

    $sender->getInventory()->addItem(Item::get(ItemIds::DIAMOND, 1));
    $sender->getPlayer()->teleport(new Vector3(0, 0, 0));
    $sender->sendMessage("bom dia");
  }
}