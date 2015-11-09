<?php

namespace ITC\DataSource\Mapper;

/**
 * User data mapper
 */
class User
{
    
    /**
     * Find a user by ID
     * 
     * @param int $userId User ID
     * @return array
     */
    public function find($userId)
    {
        
        // Get user data
        $userData = \ITC\DataSource\Database\Manager::getInstance()
            ->getMaster('ITC_Users')
            ->fetchOne(
                'SELECT * FROM `users` WHERE `userId` = :userId',
                array(
                    'userId' => (int) $userId
                )
            );
        
        // @todo needs to return a User Model with the retrieved data applied
        // instead of a plain array
        
        return $userData;
        
    }
    
}