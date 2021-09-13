<?php

namespace App\Entity;

class Organization
{

    private $name;

    private $description;

    private $users = [];
    
    public function setname($name)
    {
        $this->name = $name;
    }
    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function __construct($name, $description, $users) 
    {
        $this->users = $users;
        $this->description = $description;
        $this->name = $name;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    public function addParticipant(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeParticipant(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    public function to_array()
    {
        return get_object_vars($this);
    }
}