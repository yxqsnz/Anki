<?php

namespace Anki\Utils;

use Anki\Data\Manager;
use Anki\Plugin;
use Anki\Provider\DataProvider;
use Anki\Provider\SQLite3Provider;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class PluginConfig
{
    private Config $config;

    public function __construct(string $path, string $dbPath)
    {
        $this->config = new Config($path, Config::YAML, [
            "database" => [
                "path" => $dbPath,
                "kind" => "sqlite3"
            ]
        ]);
    }

    public function openDataProvider(PluginBase $plugin): DataProvider
    {
        $provider = match ($this->config->getNested("database.kind")) {
            "sqlite3" => new SQLite3Provider($this->config->getNested("database.path"), $plugin),
            default => throw new \Exception("database.kind: Invalid database type.")
        };

        return $provider;
    }
}
