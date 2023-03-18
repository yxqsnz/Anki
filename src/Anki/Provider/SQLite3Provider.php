<?php

namespace Anki\Provider;

use Anki\Data\DatabasePlayer;
use pocketmine\plugin\PluginBase;
use SQLite3;
use SQLite3Result;

class SQLite3Provider implements DataProvider
{
    private SQLite3 $db;

    public function __construct(string $path, PluginBase $plugin)
    {
        if (!file_exists($path)) {
            $this->db = new SQLite3($path);
            $resource = $plugin->getResource("Sqlite3.sql");
            $this->db->exec(stream_get_contents($resource));
            fclose($resource);
        } else {
            $this->db = new SQLite3($path);
        }
    }

    private function sanitize(string $nick): string
    {
        return trim(strtolower($nick));
    }


    public function addPlayer(string $nick, string $password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $this->db->prepare("INSERT INTO players (name, password) VALUES (:name, :password)");
        $stmt->bindValue(":name", $this->sanitize($nick), SQLITE3_TEXT);
        $stmt->bindValue(":password", $hash, SQLITE3_TEXT);
        $stmt->execute();
        $stmt->close();
    }

    public function getPlayer(string $nick): DatabasePlayer|null
    {
        $stmt = $this->db->prepare("SELECT * FROM players where name = :name");
        $stmt->bindValue(":name", $this->sanitize($nick), SQLITE3_TEXT);
        $result = $stmt->execute();

        if ($result instanceof SQLite3Result) {
            $data = $result->fetchArray(SQLITE3_ASSOC);
            $result->finalize();
            $stmt->close();

            if ($data) {
                return new DatabasePlayer($nick, $data["password"]);
            }
        }

        return null;
    }

    public function playerExists(string $nick): bool
    {
        return $this->getPlayer($nick) !== null;
    }

    public function close()
    {
        $this->db->close();
    }
}
