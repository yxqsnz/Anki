<?php

namespace Anki\Data;

class DatabasePlayer
{
    public string $name;
    public string $password;
    public string $lastIP;
    public ?int $createdOn;
    public ?int $loginExpire;
    public ?int $lastLogin;

    public function __construct(
        string $name,
        string $password,
        string $lastIP,
        ?int $createdOn,
        ?int $loginExpire,
        ?int $lastLogin
    ) {
        $this->name = $name;
        $this->password = $password;
        $this->lastIP = $lastIP;
        $this->lastLogin = $lastLogin;
        $this->createdOn = $createdOn;
        $this->loginExpire = $loginExpire;
    }
}
