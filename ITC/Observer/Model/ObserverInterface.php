<?php

// Define namespace
namespace ITC\Observer\Model;

interface ObserverInterface
{
    
    /**
     * Notify method called from the publisher
     */
    public function notify($user);
    
}
