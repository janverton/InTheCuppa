<?php

namespace Tests\ITC\DataSource\Model;

require_once __DIR__ . '/../../../UnitTest.php';

use ITC\DataSource\Model\User;
use ITC\DataSource\Model\Game;

/**
 * @coversDefaultClass Game
 * @covers ::<protected>
 */
class GameTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * A game instance should be created
     * 
     * @covers ::__construct
     * @test
     */
    public function getGameInstance()
    {
        
        $game = new Game();
        $this->assertInstanceOf('\ITC\DataSource\Model\Game', $game);
        
    }
    
    /**
     * Add a user to the game
     * 
     * @covers ::addUser
     * @test
     */
    public function addUser()
    {
        
        $user = new User(1);
        
        $game = new Game();
        $game->addUser($user);
        
        $users = $game->getUsers();
        
        // Assert user 1 has been added
        $this->assertInternalType('array', $users);
        $this->assertCount(1, $users);
        $this->assertInstanceOf('\ITC\DataSource\Model\User', $users[1]);
        
    }
    
    /**
     * A game should at least have two players
     * 
     * @expectedException \DomainException
     * 
     * @covers ::start
     * @test
     */
    public function startGameWithTooLittePlayers()
    {
        
        $game = new Game();
        $game->start();
        
    }
    
    /**
     * When the game is started the scores should be blank
     * 
     * @covers ::getScore
     * @test
     */
    public function startAGameResetsScores()
    {
        
        $john = new User(1);
        $alistair = new User(2);
        
        // Init game
        $game = new Game();
        $game
            ->addUser($john)
            ->addUser($alistair)
            ->start();
        
        // Scores should be empty
        $this->assertEquals(
            array(
                1 => array('score' => 0, 'shots' => 0, 'accuracy' => 0),
                2 => array('score' => 0, 'shots' => 0, 'accuracy' => 0)
            ),
            $game->getScoreSheet()
        );
        
    }
    
    /**
     * Taking a shot checks the game state. This will result in an exception
     * when the game has not been started yet
     * 
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Game state (0) prevents executing this method (ITC\DataSource\Model\Game::shotHit)
     * 
     * @covers ::shotHit
     * @test
     */
    public function takeAShotWithoutStarting()
    {
        
        $john = new User(1);

        // Take a shot without starting the game
        $game = new Game();
        $game->addUser($john)
            ->shotHit($john);
    }
    
    /**
     * When a shot results in a hit, the score sheet should be updated 
     * accordingly
     * 
     * @covers ::shotHit
     * @covers ::getScoreSheet
     * @covers ::getScore
     * @test
     */
    public function shotHitUpdatesScoreSheet()
    {
        
        $john = new User(1);
        $alistair = new User(2);
        
        // Setup game
        $game = new Game();
        $game->addUser($john)
            ->addUser($alistair)
            ->start();
        
        // John hits a shot
        $game->shotHit($john);
        
        // Scores should be empty
        $this->assertEquals(
            array(
                1 => array('score' => 1, 'shots' => 1, 'accuracy' => 100),
                2 => array('score' => 0, 'shots' => 0, 'accuracy' => 0)
            ),
            $game->getScoreSheet()
        );
        
    }
    
    /**
     * When a shot results in a miss, the score sheet should be updated
     * accordingly
     * 
     * @covers ::shotMiss
     * @covers ::getScoreSheet
     * @test
     */
    public function shotMissUpdatesScore()
    {
        
        $john = new User(1);
        $alistair = new User(2);
        
        // Setup game
        $game = new Game();
        $game->addUser($john)
            ->addUser($alistair)
            ->start();
        
        // Alistair misses a shot
        $game->shotMiss($alistair);
        
        // Scores should be empty
        $this->assertEquals(
            array(
                1 => array('score' => 0, 'shots' => 0, 'accuracy' => 0),
                2 => array('score' => 0, 'shots' => 1, 'accuracy' => 0)
            ),
            $game->getScoreSheet()
        );
        
    }
    
}