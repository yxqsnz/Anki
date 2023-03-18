<?php

namespace Anki\Provider;

use Anki\Data\DatabasePlayer;

interface DataProvider
{
    // Password hashing is assured by Provider.
    public function addPlayer(string $nick, string $password);
    public function getPlayer(string $nick): DatabasePlayer|null;
    public function playerExists(string $nick): bool;

    public function close();
}
