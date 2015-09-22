<?php

namespace ITC\DataSource\Model;

class User
{
    
    protected $userId = 0;

    /**
     * Get a user
     * 
     * @param int $userId User ID
     */
    public function __construct($userId)
    {
        $this->userId = (int) $userId; 
    }
    
    /**
     * Get user ID
     * 
     * @return int User Id
     * @throws \DomainException
     */
    public function getUserId()
    {
        if (!$this->userId) {
            throw new \DomainException('User has no ID');
        }
        
        return $this->userId;
    }
}