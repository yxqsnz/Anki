<?php

namespace Anki\Utils;

use pocketmine\utils\Config;

class CustomMessages
{
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function format(string $key, ...$args): string
    {
        return sprintf($this->config->getNested("messages." . $key), ...$args);
    }
}
