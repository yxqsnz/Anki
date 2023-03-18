<?php

namespace Anki;

use Anki\Commands\Register;
use Anki\Data\Manager;
use Anki\Utils\PluginConfig;
use pocketmine\plugin\PluginBase;

class Plugin extends PluginBase
{
  private Manager $manager;



  function onEnable()
  {
    $dbPath = $this->getDataFolder() . "Anki.db";
    $config = new PluginConfig($this->getDataFolder() . "config.yml", $dbPath);
    $dbProvider = $config->openDataProvider($this);
    $this->manager = new Manager($this, $dbProvider);

    $this->getLogger()->info("Starting... ");
    $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    $this->getServer()->getCommandMap()->register("anki", new Register($this->manager));
  }

  function onDisable()
  {
    $this->manager->close();
  }
}
