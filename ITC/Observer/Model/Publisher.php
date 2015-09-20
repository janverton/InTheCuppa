<?php

// Define namespace 
namespace ITC\Observer\Model;

class Publisher implements PublisherInterface 
{
    /**
     * List of observers
     * 
     * @var Array $observers
     */
    protected $observers = array();
    
    // Implmenet singleton
    protected function __construct(){}
    
    public static function getInstance()
    {
        // Initiate instance
        static $instance = null;
        
        // Check if instance is set
        if(null === $instance) {
            $instance = new static();
        }
        
        // Return instance
        return $instance;
        
    }
    
    /**
     * Subscribe observer to dersired object
     * 
     * @param object $object
     * @param object $observer
     */
    public static function registerObserver($object, $observer)
    {
        // Get instance of self
        $instance = PublisherInterface::getInstance();
        
        // Assign object unique id
        $objectId = \spl_object_hash($object);
        
        // Add observer to the objects subscribers
        $instance->observers[$objectId][] = $observer;
        
    }
    
    /**
     * A publisher object passes itself in to notify its observers
     * 
     * @param object $object
     */
    public static function notifyObservers($object)
    {
        
        // Get instance of self
        $instance = PublisherInterface::getInstance();
        
        // Get the objects hash
        $objectId = \spl_object_hash($object);
        
        // Get the observers of the object
        $observers = $instance->observers[$objectId];
        
        // Iterate through each object and notify it
        foreach($observers as $observer) {
            
            $observer->notify();
            
        }
        
    }


}

