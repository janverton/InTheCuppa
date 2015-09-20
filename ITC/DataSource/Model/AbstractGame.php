<?php

namespace ITC\DataSource\Model;

abstract class AbstractGame 
{
    
    /**
     * Add a user to the game
     * 
     * @param User $user
     * @return Game
     */
    public function addUser(User $user)
    {
        
        // Add a user and clear its score sheet
        $this->users[$user->getUserId()] = $user;
        $this->clearScore($user);
        
        return $this;
        
    }
        
    /**
     * Start the game
     * 
     * @throws \DomainException
     * @returns Game
     */
    public function start()
    {
        
        // Assert enough players are available
        if (2 > \count($this->users)) {
            throw new \DomainException(
                'A game should have at least 2 players'
            );
        }
        
        // Reset score sheet
        $this->scores = array();
        
        // Init scores
        foreach ($this->users as $user) {
            
            // Clear user score
            $this->clearScore($user);
            
        }
        
        // Change game state
        $this->state = self::STATE_STARTED;
        
        return $this;
        
    }
    
    /**
     * Get the users which play this game
     * 
     * @return array
     */
    public function getUsers()
    {
        return $this->users;
    }
    
    abstract function getScoreSheet();
    
    abstract function getScore(User $user);
    
    
}
