<?php

namespace Anki;

use pocketmine\event\EventHandler;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;

class EventListener implements Listener
{
  #[EventHandler()]
  public function onPlayerJoin(PlayerJoinEvent $event)
  {
    $event->getPlayer()->sendMessage("A");
  }

  #[EventHandler()]
  public function onPlayerMove(PlayerMoveEvent $event)
  {
    $event->setCancelled(true);
    $event->getPlayer()->onGround = true;
  }
}
