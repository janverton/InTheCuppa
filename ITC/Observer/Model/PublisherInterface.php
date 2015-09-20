<?php

// Define namespace 
namespace ITC\Observer\Model;

interface PublisherInterface
{
    
    public static function getInstance();
    
    /**
     * Subscribe observer to dersired object
     * 
     * @param object $object
     * @param object $observer
     */
    public static function registerObserver($object, $observer);
    
    /**
     * A publisher object passes itself in to notify its observers
     * 
     * @param object $object
     */
    public static function notifyObservers($object);
    
    /**
     * Remove a observer from its list
     * 
     * @param object $object object that it is subscribed too
     * @param obkect $observer Observer wished to be removed
     */
    //public static function removeObserver($object, $observer);
}