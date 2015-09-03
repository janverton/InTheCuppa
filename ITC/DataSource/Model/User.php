<?php

namespace ITC\DataSource\Model;

class User
{
    
    protected $userId = 0;

    public function __construct($userId)
    {
        $this->userId = (int) $userId; 
    }
    
    public function getUserId()
    {
        if (!$this->userId) {
            throw new \DomainException('User has no ID');
        }
        
        return $this->userId;
    }
}