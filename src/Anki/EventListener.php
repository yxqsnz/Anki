<?php

namespace Anki;

use Anki\Data\Manager;
use pocketmine\event\EventHandler;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;

class EventListener implements Listener
{
  private Manager $manager;

  public function __construct(Manager $manager)
  {
    $this->manager = $manager;
  }

  #[EventHandler()]
  public function onPlayerJoin(PlayerJoinEvent $event)
  {
    $this->manager->players->addPlayer($event->getPlayer());
    $event->getPlayer()->sendMessage("Olá, use §2/login <SENHA>§f ou §2/register <SENHA>§f para entrar no servidor.");
  }

  public function OnPlayerQuit(PlayerQuitEvent $event)
  {
    $this->manager->players->closePlayer($event->getPlayer());
  }

  #[EventHandler()]
  public function onPlayerMove(PlayerMoveEvent $event)
  {
    $event->setCancelled(true);
    $event->getPlayer()->onGround = true;
  }
}
