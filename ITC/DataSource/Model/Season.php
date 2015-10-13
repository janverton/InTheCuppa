<?php

// Define namespaces
namespace ITC\DataSource\Model;
use ITC\Observer\Model\ObserverInterface;

class Season extends AbstractGame implements ObserverInterface
{
    
    /**
     * A season point is achieved when notifyObservers is called in the Game class
     * 
     * @param \ITC\DataSource\Model\User $user
     */
    public function incrementSeasonPoint(User $user)
    {
        
        // Assert the game is started
        $this->assertGameState(self::STATE_STARTED);
        
        //Update the score
        $this->scores[$user->getUserId()]['seasonPoints']++;
        
        // Check if season score reaches 7 the game ends
        if (7 === $this->scores[$user->getUserId()]['seasonPoints']) {
            
            // End season
            $this->end();
        }
        
        return $this;
    }

    /**
     * 
     * 
     * @param \ITC\DataSource\Model\User $user
     * @return $array Season score
     */
    public function getScore(User $user) {
        
        // Get score
        $score = $this->scores[$user->getUserId()];
        
        // ASK JAN *only thing changed is the score array index to seasonScore*
        
        // Add shot accuracy
        if (0 < $score['shots']) {
            $score['accuracy'] = ($score['seasonPoints'] / $score['shots']) * 100;
        } else {
            $score['accuracy'] = 0;
        }
        
        return $score;
        
    }

    public function clearScore(User $user) {
        
        // Empty score sheet
        $this->scores[$user->getUserId()] = array(
            'seasonPoints' => 0,
            'shots' => 0
        );
        
        return $this;
    }
    
    /**
     * End Season
     * 
     * @return Season
     */
    public function end()
    {
        $this->state = self::STATE_FINISHED;
        // Notify observers?
        return $this;
    }

    /**
     * Called by the game class when user gets to 7 points
     * 
     * @param User $user User who one the season
     * @return Season Implement fluent interface
     */
    public function notify($user) {
        
        // Increment the user season point
        $this->incrementSeasonPoint($user);
        
        return $this;
        
    }

}

