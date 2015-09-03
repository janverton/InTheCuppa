<?php

namespace ITC;

class Bootstrap
{
    
    /**
     * Bootstrap ITC
     */
    public function __construct()
    {
        // Init class autoloading
        \spl_autoload_register(
            function ($class) {
            
                // Assert \ITC namespace is set to prevent collisions with
                // other autoloaders
                if (false === strpos($class, 'ITC\\')) {
                    return;
                }
            
                // Define class file path
                $path = \realpath(
                    \str_replace(
                        '\\',
                        '/',
                        __DIR__ . '/../' . $class . '.php'
                    )
                );
                
                // Assert path exists
                if ($path) {
                    
                    // Include class
                    include $path;
                    
                } else {
                
                    // Path is not found
                    throw new \ErrorException(
                        'File does not exist: ' . __DIR__ . '/../' . $class
                        . '.php'
                    );
                    
                }
                
            }
        );
        
    }
    
}