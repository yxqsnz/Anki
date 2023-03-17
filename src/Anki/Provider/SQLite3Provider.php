<?php

namespace Anki\Provider;

use SQLite3;

class SQLite3Provider implements DataProvider
{
    private SQLite3 $db;

    public function __construct(string $path)
    {
        $this->db = new SQLite3($path);
    }
}
