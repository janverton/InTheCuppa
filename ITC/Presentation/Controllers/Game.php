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
        
        // Get game instanance
        // @todo gameId should be used for loading from DB, as should the user be
        $game = new \ITC\DataSource\Model\Game($gameId);
        $game->addUser(new \ITC\DataSource\Model\User(1));
        
        // Get scoresheet overview
        $this->response->setBody($game->getScoreSheet());
        
    }
    
}
