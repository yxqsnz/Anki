<?php

namespace Anki;

use Anki\Commands\Login;
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
    $this->manager = new Manager($this, $config->getCustomMessages(), $dbProvider);
    $this->getServer()->getPluginManager()->registerEvents(new EventListener($this->getServer(), $this->manager), $this);
    $cmdMap = $this->getServer()->getCommandMap();

    $cmdMap->register("anki::register", new Register($this->manager));
    $cmdMap->register("anki::login", new Login($this->manager));
  }

  function onDisable()
  {
    $this->manager->close();
  }
}
