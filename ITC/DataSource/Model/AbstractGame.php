<?php

namespace ITC\DataSource\Model;

abstract class AbstractGame 
{
    
    const STATE_NOT_STARTED = 0;
    const STATE_STARTED = 1;
    const STATE_FINISHED = 2;
    
    /**
     * The users which play this game
     * 
     * @var array
     */
    protected $users = array();
    
    /**
     * Scores sheets
     * 
     * @var array
     */
    protected $scores = array();
    
    /**
     * Current game state
     * 
     * @var int
     */
    protected $state = self::STATE_NOT_STARTED;
    
    
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
    
    /**
     * Get score sheet for all users
     * 
     * @param \ITC\DataSource\Model\User $user
     * @return array
     */
    public function getScoreSheet()
    {
               
        $scoreSheet = array();
        
        // Get scores for all users
        foreach ($this->users as $user) {
            $scoreSheet[$user->getUserId()] = $this->getScore($user);
        }
        
        return $scoreSheet;
    }
    
    /**
     * Assert the current game state matches the given state
     * 
     * @param int $state Required game state
     * @throws \DomainException
     */
    protected function assertGameState($state)
    {
        
        // Assert game state 
        if (!\is_numeric($state) || $this->state !== (int) $state) {
            
            // Get calling method
            $trace = \debug_backtrace();
            $caller = $trace[1]['function'];
            
            throw new \DomainException(
                'Game state (' . $this->state . ') prevents'
                . ' executing this method (' . __CLASS__ . '::' . $caller . ')'
            );
            
        }
        
    }
    
    // Abstract get score function
    abstract protected function getScore(User $user);
    
    // Abstract clear score function 
    abstract protected function clearScore(User $user);
    
    // Abstract end season or game function 
    abstract public function end();
    
}
