<?php

namespace Tests\ITC\Presentation\Http;

require_once __DIR__ . '/../../../UnitTest.php';

use ITC\Presentation\Http\Request;

/**
 * @coversDefaultClass Request
 * @covers ::<protected>
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Parse key value pairs when string/numeric pattern occurs so the values
     * can be retrieved by their keys
     * 
     * /season/1/winner/2 
     * 
     * Can be retrieved by<br>
     * <code>
     * $request->getSegment('season');
     * </code>
     * 
     * @covers ::getSegment
     * 
     * @test
     */
    public function getSegment()
    {
        
        // Parse request
        $request = new Request('get', array('url' => '/season/1/winner/2'));
        
        // Assert season and winner key/value pairs are set
        $this->assertEquals(1, $request->getSegment('season'));
        $this->assertEquals(2, $request->getSegment('winner'));
        
    }
    
    /**
     * Parse key value pairs when string/numeric pattern occurs so the values
     * can be retrieved by their keys
     * 
     * /season/winner/2 
     * 
     * Can be retrieved by<br>
     * <code>
     * $request->getSegment('season'); => false<br>
     * $request->getSegment('winner'); => 2
     * </code>
     * 
     * @covers ::getSegment
     * 
     * @test
     */
    public function getSegmentWithoutValue()
    {
        
        // Parse request
        $request = new Request('get', array('url' => '/season/winner/2'));
        
        // Assert season and winner key/value pairs are set
        $this->assertFalse($request->getSegment('season'));
        $this->assertEquals(2, $request->getSegment('winner'));
        
    }
    
    /**
     * Parse key value pairs when string/numeric pattern occurs so the values
     * can be retrieved by their keys
     * 
     * /32/56/season/1
     * 
     * Can be retrieved by<br>
     * <code>
     * $request->getSegment('32'); => 56<br>
     * $request->getSegment('season'); => 1
     * </code>
     * 
     * @covers ::getSegment
     * 
     * @test
     */
    public function getSegmentWithLeadingNumbers()
    {
        
        // Parse request
        $request = new Request('get', array('url' => '/32/56/season/1'));
        
        // Assert season and winner key/value pairs are set
        $this->assertEquals(56, $request->getSegment('32'));
        $this->assertEquals(1, $request->getSegment('season'));
        
    }
    
    /**
     * Parse key value pairs when string/numeric pattern occurs so the values
     * can be retrieved by their keys
     * 
     * /season/2?foo=bar 
     * 
     * Can be retrieved by<br>
     * <code>
     * $request->getSegment('season'); => 2
     * </code>
     * 
     * @covers ::getSegment
     * 
     * @test
     */
    public function getSegmentWithAdditionalParameters()
    {
        
        // Parse request
        $request = new Request('get', array('url' => '/season/2?foo=bar'));
        
        // Assert season key/value pair is set
        $this->assertEquals(2, $request->getSegment('season'));
        
    }
    
    /**
     * Get the available url segment keys when a url is set
     * 
     * @covers ::getSegmentKeys
     * 
     * @test
     */
    public function getSegmentKeys()
    {
        
        // Parse request
        $request = new Request('get', array('url' => '/season/1/winner/2'));
        
        // Assert season and winner key/value pairs are set
        $this->assertEquals(
            array('season', 'winner'),
            $request->getSegmentKeys()
        );
    }
    
    /**
     * Get the available url segment keys when no url has been set
     * 
     * @covers ::getSegmentKeys
     * 
     * @test
     */
    public function getSegmentKeysWithoutUrl()
    {
        
        // Parse request
        $request = new Request('get', array('url' => '/'));
        
        // Assert season and winner key/value pairs are set
        $this->assertCount(0, $request->getSegmentKeys());
        
    }
}