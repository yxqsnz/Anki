<?php

namespace Anki\Data;

use pocketmine\Player;

class ManagedPlayer
{
}

class PlayerList extends \ArrayObject
{
  public function offsetSet(mixed $key, mixed $value): void
  {

    if ($value instanceof ManagedPlayer) {
      parent::offsetSet($key, $value);
      return;
    }

    throw new \InvalidArgumentException('Value must be a ManagedPlayer');
  }
}

class PlayerManager
{
  private PlayerList $players;
  private Manager $manager;

  public function __construct(Manager $manager)
  {
    $players = new PlayerList();
  }

  public function addPlayer(Player $player)
  {
    $this->players->append($player);
  }
}
