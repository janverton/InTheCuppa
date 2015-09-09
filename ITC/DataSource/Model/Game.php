<?php

namespace ITC\DataSource\Model;

/**
 * A classic game of "In the cuppa"
 */
class Game
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
     * Get the users which play this game
     * 
     * @return array
     */
    public function getUsers()
    {
        return $this->users;
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
     * End game
     * 
     * @return Game
     */
    public function end()
    {
        $this->state = self::STATE_FINISHED;
        return $this;
    }
    
    /**
     * A shot hit
     * 
     * @param User $user
     * @return Game
     */
    public function shotHit(User $user)
    {
        
        // Assert the game is started
        $this->assertGameState(self::STATE_STARTED);
        
        // Update the users score sheet
        $this->scores[$user->getUserId()]['score']++;
        $this->scores[$user->getUserId()]['shots']++;
        
        // When game score reaches 7 the game ends
        if (7 === $this->scores[$user->getUserId()]['score']) {
            $this->end();
        }
        
        return $this;
        
    }
    
    /**
     * A shot missed
     * 
     * @param User $user
     * @return Game
     */
    public function shotMiss(User $user)
    {
        
        // Assert the game is started
        $this->assertGameState(self::STATE_STARTED);
        
        // Update the users score sheet
        $this->scores[$user->getUserId()]['shots']++;
        
        return $this;
        
    }
    
    /**
     * Get score sheets for all users
     * 
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
     * Get score for the given user
     * 
     * @param User $user
     * @return array
     */
    protected function getScore(User $user)
    {
        
        // Get score
        $score = $this->scores[$user->getUserId()];
        
        // Add shot accuracy
        if (0 < $score['shots']) {
            $score['accuracy'] = ($score['score'] / $score['shots']) * 100;
        } else {
            $score['accuracy'] = 0;
        }
        
        return $score;
        
    }
    
    /**
     * Clear the users' score sheet
     * 
     * @param User $user
     * @return Game
     */
    protected function clearScore(User $user)
    {
        
        // Empty score sheet
        $this->scores[$user->getUserId()] = array(
            'score' => 0,
            'shots' => 0
        );
        
        return $this;
        
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
    
}