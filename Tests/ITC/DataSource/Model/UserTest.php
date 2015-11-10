<?php

namespace Tests\ITC\DataSource\Model;

require_once __DIR__ . '/../../../UnitTest.php';

use ITC\DataSource\Model\User;

/**
 * @coversDefaultClass User
 * @covers ::<protected>
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * A model mapper uses the setData to 'load' the data model. This should
     * not be regarded as a data change
     * 
     * @covers ::isDirty
     * @covers ::setData
     * 
     * @test
     */
    public function loadDataToUserModel()
    {
        
        // Get user instance
        $user = new User();
        
        // Assert the object has no changes
        $this->assertFalse($user->isDirty());
        
        // Set some fake data
        $user->setData(array('userId' => 1));
        
        // Assert the object still has no changes
        $this->assertFalse($user->isDirty());
        
    }
    
    /**
     * Magic class properties are available to the model. When changed, the
     * model should be marked dirty
     * 
     * @covers ::__set
     * @covers ::__get
     * @covers ::isDirty
     * 
     * @test
     */
    public function changeUserModelData()
    {
        
        // Get user instance
        $user = new User();
        $user->userId = 1;
        
        // Assert value has been set
        $this->assertSame(1, $user->userId);
        
        // Assert user model has been changed
        $this->assertTrue($user->isDirty());
        
    }
    
    /**
     * When a data set is reloaded to the model, the dirty state should be
     * reset
     * 
     * @covers ::__set
     * @covers ::setData
     * @covers ::isDirty
     * 
     * @test
     */
    public function resetChangedUserModelData()
    {
        
        // Get user instance
        $user = new User();
        
        // Change user data
        $user->userId = 1;
        
        // Assert user model has been changed
        $this->assertTrue($user->isDirty());
        
        // Reload data
        $user->setData(array('userId' => 1));
        
        // Assert user model has been changed
        $this->assertFalse($user->isDirty());
        
    }
    
}