<?php

namespace Tests\ITC\DataSource\Model;

require_once __DIR__ . '/../../../UnitTest.php';

use ITC\DataSource\Model\Season;

/**
 * @coversDefaultClass \ITC\DataSource\Model\Season
 * @covers ::<protected>
 */
class SeasonTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Get instance of game
     * 
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