<?php

namespace Anki;

use Anki\Data\Manager;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\EventHandler;
use pocketmine\event\EventPriority;
use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use pocketmine\Server;

class EventListener implements Listener
{
  private Manager $manager;
  private Server $server;

  public function __construct(Server $server, Manager $manager)
  {
    $this->manager = $manager;
    $this->server = $server;
  }

  private function isPlayerAuth(Player $player)
  {
    return $this->manager->players->isPlayerAuthenticated($player->getName());
  }

  #[EventHandler(priority: EventPriority::MONITOR)]
  public function onPlayerJoin(PlayerJoinEvent $event)
  {
    if ($this->isPlayerAuth($event->getPlayer())) {
      return;
    }

    $this->manager->players->addPlayer($event->getPlayer());
    $event->getPlayer()->sendMessage($this->manager->fmt("welcome.message"));
  }

  #[EventHandler(priority: EventPriority::HIGHEST)]
  public function onPlayerPreLogin(PlayerPreLoginEvent $event)
  {
    $player = $event->getPlayer();
    foreach ($this->server->getOnlinePlayers() as $onlinePlayer) {
      if ($player !== $onlinePlayer) {
        if ($this->isPlayerAuth($player)) {
          $event->setCancelled(true);
          $player->kick($this->manager->fmt("kick.alreadyLoggedIn", $player->getName()), false);
          return;
        }
      }
    }
  }

  #[EventHandler(priority: EventPriority::MONITOR)]
  public function onPlayerQuit(PlayerQuitEvent $event)
  {
    if ($event->getPlayer()->loggedIn) {
      $this->manager->players->closePlayer($event->getPlayer());
    }
  }

  #[EventHandler(priority: EventPriority::MONITOR)]
  public function onPlayerMove(PlayerMoveEvent $event)
  {
    if (!$this->isPlayerAuth($event->getPlayer())) {
      $event->setCancelled(true);
      $event->getPlayer()->onGround = true;
    }
  }

  #[EventHandler(priority: EventPriority::MONITOR)]
  public function onPlayerInteract(PlayerInteractEvent $event)
  {
    if (!$this->isPlayerAuth($event->getPlayer())) {
      $event->setCancelled(true);
    }
  }

  #[EventHandler(priority: EventPriority::MONITOR)]
  public function onPlayerDropItem(PlayerDropItemEvent $event)
  {
    if (!$this->isPlayerAuth($event->getPlayer())) {
      $event->setCancelled(true);
    }
  }

  #[EventHandler(priority: EventPriority::MONITOR)]
  public function onPlayerItemConsume(PlayerItemConsumeEvent $event)
  {
    if (!$this->isPlayerAuth($event->getPlayer())) {
      $event->setCancelled(true);
    }
  }

  #[EventHandler(priority: EventPriority::MONITOR)]
  public function onEntityDamage(EntityDamageEvent $event)
  {
    if ($event->getEntity() instanceof Player and !$this->isPlayerAuth($event->getEntity())) {
      $event->setCancelled(true);
    }
  }

  #[EventHandler(priority: EventPriority::MONITOR)]
  public function onBlockBreak(BlockBreakEvent $event)
  {
    if ($event->getPlayer() instanceof Player and !$this->isPlayerAuth($event->getPlayer())) {
      $event->setCancelled(true);
    }
  }

  #[EventHandler(priority: EventPriority::MONITOR)]
  public function onBlockPlace(BlockPlaceEvent $event)
  {
    if ($event->getPlayer() instanceof Player and !$this->isPlayerAuth($event->getPlayer())) {
      $event->setCancelled(true);
    }
  }


  #[EventHandler(priority: EventPriority::MONITOR)]
  public function onPickupItem(InventoryPickupItemEvent $event)
  {
    $player = $event->getInventory()->getHolder();
    if ($player instanceof Player and !$this->isPlayerAuth($player)) {
      $event->setCancelled(true);
    }
  }
}
