<?php

namespace Anki\Data;

use pocketmine\Player;
use pocketmine\Server;

class ManagedPlayer
{
  private Manager $manager;
  public string $nick;
  public ?DatabasePlayer $dbPlayer;
  public bool $isAuthenticated = false;
  public bool $isRegistred = false;

  public function __construct(string $nick,  Manager $manager)
  {
    $this->manager = $manager;
    $this->nick = $nick;
    $this->dbPlayer = $manager->data->getPlayer($nick);
    $this->isRegistred = $this->dbPlayer !== null;
  }


  public function register(Player $player, string $password)
  {
    if ($this->isRegistred) {
      throw new \Exception("Player is already registred!");
    }

    $regTime = time();
    $expire = $regTime + mktime(2);
    $dbPlayer = new DatabasePlayer($player->getName(), $password, $player->getAddress(), $regTime, $expire, $regTime);
    $this->manager->data->addPlayer($dbPlayer);
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
      $this->dbPlayer->lastLogin = time();
      $this->dbPlayer->loginExpire = time() + mktime(2);
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
      return $managedPlayer->register($player, $password);
    }
  }

  public function login(Player $player, string $password): ?bool
  {
    $managedPlayer = $this->findPlayer($player->getName());

    if ($managedPlayer !== null) {
      $res = $managedPlayer->login($password);

      if ($res) {
        $this->manager->data->updatePlayer($managedPlayer->dbPlayer);
      }

      return $res;
    }

    return null;
  }

  public function tryLoginByIP(Player $player): bool
  {
    $managedPlayer = $this->findPlayer($player->getName());

    if ($managedPlayer !== null && $managedPlayer->dbPlayer !== null) {
      $auth = ($managedPlayer->dbPlayer->lastIP == $player->getAddress()
        && $managedPlayer->dbPlayer->lastLogin < $managedPlayer->dbPlayer->loginExpire
      );

      $managedPlayer->isAuthenticated = $auth;
      return $auth;
    }

    return false;
  }
}
