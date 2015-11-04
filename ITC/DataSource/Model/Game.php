<?php

namespace ITC\DataSource\Model;
use \ITC\Observer\Model\PublisherInterface;

/**
 * A classic game of "In the cuppa"
 */
class Game extends AbstractGame implements PublisherInterface
{
    
    /**
     * List of observers
     * 
     * @var array
     */
    protected $observers = array();
    
    /**
     * End game
     * 
     * @return Game
     */
    public function end()
    {
        $this->state = self::STATE_FINISHED;
        // Notify observers?
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
            // Season point achieved
            
            // Notify observers
            $this->notifyObservers($user);
            
            // Finish game
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
     * Iterate through each observer notifying them of change
     */
    public function notifyObservers() {
        
        // Iterate through observer list
        foreach ($this->observers as $observer) {
            
            // Call notify on observer
            $observer->notify();
            
        }
        
        // Implement fluent interface
        return $this;
    }

    /**
     * Register obsvers
     * 
     * @param Object $observer
     */
    public function registerObserver($observer) {
        
        // Append observer to list of observers
        \array_push($this->observers, $observer);
        
        // Implment fluent interface
        return $this;
        
    }

}