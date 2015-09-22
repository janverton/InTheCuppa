<?php

namespace ITC\Presentation\Controllers;

class Heartbeat extends ControllerAbstract
{
    
    public function getIndexAction()
    {
        
        $this->response->setBody('I Beat!');
        
    }
    
}
