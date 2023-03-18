<?php

namespace Anki\Utils;


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
            ],

            "messages" => [
                "kick" => ["alreadyLoggedIn" => "§bAnki Login\n\n§f%s\n§cVocê já está logado aqui."],

                "welcome" => [
                    "message" => "Seja bem vindo, use §2/logar <SENHA>§f ou §2/registrar <SENHA>§f para entrar no servidor.",
                    "loggedByIP" => "§aSeja bem vindo, você foi logado automaticamente pelo seu IP."
                ],

                "login" => [
                    "success" => "Você se logou com sucesso! Bom Jogo.",
                    "incorrectPassword" => "Senha errada. Tente novamente.",
                    "notRegistred" => "Você não está registrado.",
                    "alreadyAuthenticated" => "Você já está autenticado",
                    "missingPasswordArgument" => "Você precisa botar uma senha como argumento!",
                ],

                "register" => [
                    "success" => "Você se registrou com sucesso! Bom Jogo.",
                    "alreadyRegistred" => "Você já está registrado.",
                    "alreadyAuthenticated" => "Você já está autenticado",
                    "missingPasswordArgument" => "Você precisa botar uma senha como argumento!",
                ]
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

    public function getCustomMessages(): CustomMessages
    {
        return new CustomMessages($this->config);
    }
}
