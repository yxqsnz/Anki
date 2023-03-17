<?php

namespace Anki;

use Anki\Commands\Register;
use Anki\Data\Manager;
use Anki\Utils\PluginConfig;
use pocketmine\plugin\PluginBase;

class Plugin extends PluginBase
{
  function getDatabasePath(): string
  {
    $location = $this->getDataFolder() . "Anki.db";

    if (!is_dir($this->getDataFolder())) {
      mkdir($this->getDataFolder());
    }

    return $location;
  }

  function onEnable()
  {
    $dbPath = $this->getDatabasePath();
    $config = new PluginConfig($this->getDataFolder() . "config.yml", $dbPath);
    $dbProvider = $config->openDataProvider();
    $manager = new Manager($dbProvider);

    $this->getLogger()->info("Starting... ");
    $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    $this->getServer()->getCommandMap()->register("anki", new Register());
  }
}
