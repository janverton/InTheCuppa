<?php

namespace Tests\ITC\Presentation\Http;

require_once __DIR__ . '/../../../UnitTest.php';

use ITC\Presentation\Http\Response;

/**
 * @coversDefaultClass \ITC\Presentation\Http\Response
 * @covers ::<protected>
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Sending simple successful response returns 200 header and JSON response
     * 
     * @covers ::setBody
     * @covers ::send
     * @test
     */
    public function sendOk()
    {

        // Prepare a response with a string as a body
        $response = new Response();
        $response->setBody('Foo');
        
        // String should be JSON formatted
        $this->assertEquals('"Foo"', $response->send());
        
    }
    
    /**
     * Sending an internal server error when debugging is enabled
     * 
     * @covers ::enableDebug
     * @covers ::sendInternalServerError
     * @test
     */
    public function sendInternalServerErrorWithDebug()
    {
        
        // Get response and enable debugging
        $response = new Response();
        $response->enableDebug();
        
        // Get response when an exception is is set
        $responseBody = \json_decode(
            $response->sendInternalServerError(new \Exception()),
            true
        );
        
        // Assert message and trace data are set
        $this->assertArrayHasKey('message', $responseBody);
        $this->assertArrayHasKey('trace', $responseBody);
        
    }
    
}