<?php

namespace Tests\ITC\DataSource\Mapper;

require_once __DIR__ . '/../../../UnitTest.php';

use ITC\DataSource\Mapper\User;

/**
 * @coversDefaultClass \ITC\DataSource\Mapper\User
 * @covers ::<protected>
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * A user mapper instance should be created
     * 
     * @test
     */
    public function getUserMapperInstance()
    {
        
        $user = new User();
        $this->assertInstanceOf('\ITC\DataSource\Mapper\User', $user);
        
    }

    /**
     * Find a user by its key
     * 
     * @covers ::find
     * @test
     */
    public function find()
    {
        
        $user = new User();
        
    }
    
}