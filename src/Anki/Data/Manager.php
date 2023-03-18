<?php

namespace Anki\Data;

use Anki\Data\PlayerManager;
use Anki\Plugin;
use Anki\Provider\DataProvider;

class Manager
{
    public PlayerManager $playerManager;
    public DataProvider $data;
    public Plugin       $plugin;

    public function __construct(Plugin $plugin, DataProvider $data)
    {
        $this->data = $data;
        $this->plugin = $plugin;
        $this->playerManager = new PlayerManager($this);
    }

    public function close()
    {
        $this->data->close();
    }
}
