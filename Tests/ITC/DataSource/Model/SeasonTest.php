<?php

namespace Tests\ITC\DataSource\Model;

require_once __DIR__ . '/../../../UnitTest.php';

use ITC\DataSource\Model\User;
use ITC\DataSource\Model\Game;
use ITC\DataSource\Model\Season;

/**
 * @coversDefaultClass Game
 * @covers ::<protected>
 */
class SeasonTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Get instance of game
     * 
     * @covers ::__construct
     * @test
     */
    public function getSeasonInstance()
    {
        
        $season = new Season();
        
        $this->assertInstanceOf(
            '\ITC\DataSource\Model\Season',
            $season
        );
        
    }
    
}