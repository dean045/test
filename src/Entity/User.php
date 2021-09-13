<?php

namespace App\Entity;

class User
{

    private $name;

    private $password;

    private $role = [];

    public function setname($name)
    {
        $this->name = $name;
    }
    public function getName(): ?string 
    {
        return $this->name;
    }

    public function getRole(): array
    {
        $role = $this->role;

        return array_unique($roles);
    }

    public function setRoles(array $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function __construct($name, $password, $role)
    {
        $this->role = $role;
        $this->password = $password;
        $this->name = $name;
    }

    public function to_array()
    {
        return get_object_vars($this);
    }
}