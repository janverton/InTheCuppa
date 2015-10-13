<?php

// Define namespace 
namespace ITC\Observer\Model;

interface PublisherInterface
{
    
    /**
     * Subscribe observer to dersired object
     * 
     * @param object $object
     * @param object $observer
     */
    public function registerObserver($observer);
    
    /**
     * A publisher object passes itself in to notify its observers
     * 
     * @param object $object
     */
    public function notifyObservers();
    
    /**
     * Remove a observer from its list
     * 
     * @param object $object object that it is subscribed too
     * @param obkect $observer Observer wished to be removed
     */
    //public static function removeObserver($object, $observer);
}