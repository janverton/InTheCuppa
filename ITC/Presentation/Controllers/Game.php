<?php

namespace ITC\Presentation\Controllers;

/**
 * Game controller
 */
class Game extends ControllerAbstract
{
    
    /**
     * Get game scoresheet overview
     */
    public function getIndexAction()
    {
        
        // Get the game ID
        $gameId = (int) $this->request->getSegment('game');
        
        // Get game instance
        // @todo gameId should be used for loading from DB, as should the user be
        $game = new \ITC\DataSource\Model\Game($gameId);
        $game->addUser(new \ITC\DataSource\Model\User(1));
        
        // Get scoresheet overview
        $this->response->setBody($game->getScoreSheet());
        
    }
    
    /**
     * Add a user to an unstarted game
     */
    public function postAddUserAction()
    {
        
        // Get the game ID
        $gameId = (int) $this->request->getSegment('game');
        $userId = (int) $this->request->getSegment('adduser');
        
        // Get game instance
        // @todo gameId should be used for loading from DB, as should the user be
        $game = new \ITC\DataSource\Model\Game($gameId);
        $game->addUser(new \ITC\DataSource\Model\User($userId));
        
        // Get scoresheet overview
        $this->response->setBody($game->getScoreSheet());
        
    }
    
    /**
     * Start the game
     */
    public function postStartAction()
    {
        
        // Get the game ID
        $gameId = (int) $this->request->getSegment('game');
        
        // Get game instance
        // @todo gameId should be used for loading from DB, as should the user be
        $game = new \ITC\DataSource\Model\Game($gameId);
        
        // Start the game
        $game->start();
        
        // Get scoresheet overview
        $this->response->setBody($game->getScoreSheet());
        
    }
    
    /**
     * Add a user to an unstarted game
     */
    public function postHitAction()
    {
        
        // Get the game ID
        $gameId = (int) $this->request->getSegment('game');
        
        // Get the user ID of the one who got a hit
        $userId = (int) $this->request->getSegment('hit');
        
        // Get game instance
        // @todo gameId should be used for loading from DB, as should the
        // user and starting of the match be
        $game = new \ITC\DataSource\Model\Game($gameId);
        $user1 = new \ITC\DataSource\Model\User($userId);
        $game->addUser($user1);
        $game->addUser(new \ITC\DataSource\Model\User(999));
        $game->start();
        
        // Set the user who got a hit
        $game->shotHit(new \ITC\DataSource\Model\User($user1));
        
        // Get scoresheet overview
        $this->response->setBody($game->getScoreSheet());
        
    }
    
}
