<?php

namespace Tests\ITC\DataSource\Database;

require_once __DIR__ . '/../../../UnitTest.php';

use ITC\DataSource\Database\Client;
use ITC\DataSource\Database\Exception;

/**
 * @coversDefaultClass \ITC\DataSource\Database\Client
 * @covers ::<protected>
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * A database client instance should be created
     * 
     * @covers ::__construct
     * @test
     */
    public function getClientInstance()
    {
        
        $mysqliMock = $this->getMockBuilder('\mysqli')
            ->disableOriginalConstructor()
            ->getMock();
        
        $client = new Client($mysqliMock);
        $this->assertInstanceOf('\ITC\DataSource\Database\Client', $client);
        
    }
    
    /**
     * Perform a succesful fetch all query
     * 
     * @covers ::fetchAll
     * @covers ::getQueryLog
     * @test
     */
    public function fetchAll()
    {

        // Mock mysqli instance
        $mysqliMock = $this->getMockBuilder('\mysqli')
            ->disableOriginalConstructor()
            ->setMethods(
                array('query')
            )
            ->getMock();
        
        // Mock mysqli result instance which will be returned by 
        // mysqli mock
        $mysqliResultMock = $this->getMockBuilder('\mysqli_result')
            ->disableOriginalConstructor()
            ->setMethods(
                array('fetch_assoc', 'free')
            )
            ->getMock();
        
        // Fetch resulting rows mocked
        $mysqliResultMock->expects($this->atLeastOnce())
            ->method('fetch_assoc')
            ->will(
                $this->onConsecutiveCalls(
                    array('name' => 'Gaffer'), // first row
                    false // no second row
                )
            );
        
        // Resource should be freed when done
        $mysqliResultMock->expects($this->once())
            ->method('free');
        
        // The following select query is expected to be executed
        $mysqliMock->expects($this->once())
            ->method('query')
            ->with('SELECT `name` FROM USER WHERE `userId` = 1')
            ->will($this->returnValue($mysqliResultMock));
        
        // Get client instance with the mysql mock injected
        $client = new Client($mysqliMock);
        
        // Execute query to get a user name
        $userData = $client->fetchAll(
            'SELECT `name` FROM USER WHERE `userId` = :userId',
            array('userId' => 1)
        );
        
        // Get a log of the executed queries
        $queryLog = $client->getQueryLog();
        
        // Query should be "executed" like this
        $this->assertSame(
            array('SELECT `name` FROM USER WHERE `userId` = 1'),
            $queryLog
        );
        
        // The result should be 
        $this->assertSame(array(array('name' => 'Gaffer')), $userData);
        
    }
    
    /**
     * Fetch one row succesfully
     * 
     * @covers ::fetchOne
     * @test
     */
    public function fetchOneSuccess()
    {
        
        // Mock fetchAll method for client
        $client = $this->getMockBuilder('\ITC\DataSource\Database\Client')
            ->disableOriginalConstructor()
            ->setMethods(array('fetchAll'))
            ->getMock();
        
        // Mock fetch all method to return 1 row
        $client->expects($this->once())
            ->method('fetchAll')
            ->with(
                'SELECT `name` FROM USER WHERE `userId` = :userId',
                array('userId' => 1)
            )
            ->will(
                $this->returnValue(
                    array(
                        array('name' => 'Geezer')
                    )
                )
            );
        
        // Assert only one array is returned
        $this->assertSame(
            array('name' => 'Geezer'),
            $client->fetchOne(
                'SELECT `name` FROM USER WHERE `userId` = :userId',
                array('userId' => 1)
            )
        );
        
    }
    
    /**
     * When a query is executed with more or less than one result, the
     * query will fail
     * 
     * @expectedException Exception
     * 
     * @covers ::fetchOne
     * @test
     */
    public function fetchOneFail()
    {
        
        // Mock fetchAll method for client
        $client = $this->getMockBuilder('\ITC\DataSource\Database\Client')
            ->disableOriginalConstructor()
            ->setMethods(array('fetchAll'))
            ->getMock();
        
        // Mock fetch all method to return an empty resultset
        $client->expects($this->once())
            ->method('fetchAll')
            ->will($this->returnValue(array()));
        
        // This result will be empty, which triggers an exception
        $client->fetchOne(
            'SELECT `name` FROM USER WHERE `userId` = :userId',
            array('userId' => 1)
        );
        
    }
}