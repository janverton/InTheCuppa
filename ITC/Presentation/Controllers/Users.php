<?php

namespace ITC\Presentation\Controllers;

/**
 * Users controller
 */
class Users extends ControllerAbstract
{
    
    /**
     * Get user overview
     */
    public function getIndexAction()
    {
        
        // Get user ID
        $userId = (int) $this->request->getSegment('users');
        
        // Get user mapper
        $userMapper = new \ITC\DataSource\Mapper\User();
        
        // Get scoresheet overview
        $this->response->setBody($userMapper->find($userId));
        
    }
    
}