<?php

namespace Anki\Provider;

use Anki\Data\DatabasePlayer;

interface DataProvider
{
    // Password hashing is assured by Provider.
    public function addPlayer(
        DatabasePlayer $player
    ): void;
    public function getPlayer(string $nick): ?DatabasePlayer;
    public function updatePlayer(DatabasePlayer $player): void;
    public function playerExists(string $nick): bool;

    public function close();
}
