<?php

namespace Anki;

use pocketmine\event\EventHandler;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class EventListener implements Listener
{
  #[EventHandler()]
  public function onPlayerJoin(PlayerJoinEvent $event)
  {
    $event->getPlayer()->sendMessage("A");
  }
}