<?php

namespace Anki\Data;

use Anki\Data\PlayerManager;
use Anki\Provider\DataProvider;

class Manager
{
    public PlayerManager $playerManager;
    public DataProvider $data;

    public function __construct(DataProvider $data)
    {
        $this->data = $data;
        $this->playerManager = new PlayerManager($this);
    }
}
