<?php

namespace ITC\Presentation\Controllers;

use ITC\Presentation\Http\Request;
use ITC\Presentation\Http\Response;

abstract class ControllerAbstract
{
    
    /**
     * Http request instance
     * @var Request
     */
    protected $request;
    
    /**
     * Http response instance
     * @var Response
     */
    protected $response;
    
    /**
     * Construct controller
     * 
     * @param Request $request
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    
}
