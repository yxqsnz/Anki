<?php

namespace Anki\Data;

use pocketmine\Player;

class ManagedPlayer
{
  private ?DatabasePlayer $dbPlayer;
  private Manager $manager;
  public string $nick;
  public bool $isAuthenticated = false;
  public bool $isRegistred = false;

  public function __construct(string $nick,  Manager $manager)
  {
    $this->manager = $manager;
    $this->nick = $nick;
    $this->dbPlayer = $manager->data->getPlayer($nick);
    $this->isRegistred = $this->dbPlayer !== null;
  }

  public function register(string $password)
  {
    if ($this->isRegistred) {
      throw new \Exception("Player is already registred!");
    }

    $this->manager->data->addPlayer($this->nick, $password);
    $this->isRegistred = true;
    $this->isAuthenticated = true;
    $this->dbPlayer = $this->manager->data->getPlayer($this->nick);
  }

  public function login(string $password): bool
  {
    if (!$this->isRegistred) {
      throw new \Exception("Player is not registred!");
    }

    $res = password_verify($password, $this->dbPlayer->password);

    if ($res) {
      $this->isAuthenticated = true;
    }

    return $res;
  }
}


class PlayerManager
{
  /** @var ManagedPlayer[] */
  private array $players;
  private Manager $manager;

  public function __construct(Manager $manager)
  {
    $this->players = array();
    $this->manager = $manager;
  }

  private function findPlayer(string $nick): ?ManagedPlayer
  {
    foreach ($this->players as $managedPlayer) {
      if ($managedPlayer->nick === $nick) {
        return $managedPlayer;
      }
    }

    return null;
  }

  public function addPlayer(Player $player)
  {
    $managedPlayer = new ManagedPlayer($player->getName(), $this->manager);
    array_push($this->players, $managedPlayer);
  }

  public function closePlayer(Player $player)
  {
    $managedPlayer = $this->findPlayer($player->getName());
    if ($managedPlayer !== null) {
      $idx = array_search($managedPlayer, $this->players);

      unset($this->players[$idx]);
    }
  }


  public function isPlayerRegistred(string $nick): ?bool
  {
    $managedPlayer = $this->findPlayer($nick);

    if ($managedPlayer !== null) {
      return $managedPlayer->isRegistred;
    }

    return null;
  }


  public function isPlayerAuthenticated(string $nick): ?bool
  {
    $managedPlayer = $this->findPlayer($nick);

    if ($managedPlayer !== null) {
      return $managedPlayer->isAuthenticated;
    }

    return null;
  }

  public function register(Player $player, string $password)
  {
    $managedPlayer = $this->findPlayer($player->getName());

    if ($managedPlayer !== null) {
      return $managedPlayer->register($password);
    }
  }

  public function login(Player $player, string $password): ?bool
  {
    $managedPlayer = $this->findPlayer($player->getName());

    if ($managedPlayer !== null) {
      return $managedPlayer->login($password);
    }

    return null;
  }
}
