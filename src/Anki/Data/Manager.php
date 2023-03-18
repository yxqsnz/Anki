<?php

namespace Anki\Data;

use Anki\Data\PlayerManager;
use Anki\Plugin;
use Anki\Provider\DataProvider;
use Anki\Utils\CustomMessages;

class Manager
{
    public PlayerManager  $players;
    public DataProvider   $data;
    public Plugin         $plugin;
    private CustomMessages $customMessages;

    public function __construct(Plugin $plugin, CustomMessages $customMessages, DataProvider $data)
    {
        $this->data = $data;
        $this->plugin = $plugin;
        $this->customMessages = $customMessages;
        $this->players = new PlayerManager($this);
    }

    public function fmt(string $key, ...$args): string
    {
        return $this->customMessages->format($key, ...$args);
    }

    public function close()
    {
        $this->data->close();
    }
}
