<?php

namespace Tests\ITC\Presentation;

require_once __DIR__ . '/../../UnitTest.php';

use ITC\Presentation\FrontController;

/**
 * @coversDefaultClass FrontController
 * @covers ::<protected>
 */
class FrontControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * GET  api.inthecuppa.com/heartbeat/ -> Heartbeat::getIndex()
     * 
     * @covers ::run
     * 
     * @test
     */
    public function parseGetWithDefaultAction()
    {
        
        // Mock request object
        $requestMock = $this->getMockBuilder('\ITC\Presentation\Http\Request')
            ->setMethods(array('getMethod', 'getSegmentKeys'))
            ->disableOriginalConstructor()
            ->getMock();
        $requestMock->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue('get'));
        $requestMock->expects($this->any())
            ->method('getSegmentKeys')
            ->will($this->returnValue(array('heartbeat')));
        
        // Mock response object
        $responseMock = $this->getMockBuilder('\ITC\Presentation\Http\Response')
            ->setMethods(array('send', 'setBody'))
            ->getMock();
        $responseMock->expects($this->once())
            ->method('setBody')
            ->with('I Beat!');
        $responseMock->expects($this->once())
            ->method('send');
        
        $frontController = new FrontController($requestMock, $responseMock);
        $frontController->run();
        
    }
        
}