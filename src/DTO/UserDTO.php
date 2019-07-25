<?php

namespace App\DTO;

class UserDTO 
{
	private $id;
	private $name;
    private $email;
    private $roleId;
	
    public function __construct(int $id, string $name, string $email, int $roleId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->roleId = $roleId;
    }
	
    public function getId(): int
    {
        return $this->id;
    }
	
    public function getName(): string
    {
        return $this->name;
    }
	
    public function getEmail(): string
    {
        return $this->email;
    }
	
    public function getRoleId(): int
    {
        return $this->roleId;
    }
}
