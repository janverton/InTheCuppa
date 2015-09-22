<?php

namespace ITC\Presentation;

// Includes
use \ITC\Presentation\Http\Request;
use \ITC\Presentation\Http\Response;

/**
 * The front controller is responsible for instantiating a controller and
 * executing the selected action. These are based on the segmentKeys returned
 * from the request object.
 */
class FrontController
{
    
    /**
     * Request instance
     * 
     * @var Request
     */
    protected $request;
    
    /**
     * Response instance
     * 
     * @var Response instance
     */
    protected $response;
    
    /**
     * Instantiate front controller. Request and response objects are optional
     * 
     * @param Request  $request
     * @param Response $response
     */
    public function __construct(
        Request $request = null,
        Response $response = null
    ) {
        
        // Lazy load request
        if (!isset($request)) {
            
            // Set default method
            $method = 'get';
            
            // Only overwrite request method when it is set correctly
            if (\array_key_exists('REQUEST_METHOD', $_SERVER)) {
                
                // Get request method
                $method = \strtolower($_SERVER['REQUEST_METHOD']);
                
            }
            
            // Create request
            $this->request = new Request($method, $_REQUEST);
            
        } else { 
            $this->request = $request;
        }
        
        // Lazy load response
        if (!isset($response)) {
            $this->response = new Response();
        } else {
            $this->response = $response;
        }
        
        // Enable debug mode when set
        if ('on' === $this->request->getParam('debug')) {
            $this->response->enableDebug();
        }
    }
    
    /**
     * Define and execute the controller::action derived from the available
     * url segments
     */
    public function run()
    {
        
        // Get parsed segments
        $segments = $this->request->getSegmentKeys();
        
        // Extract controller when available
        $controllerName = 'Heartbeat';
        if (\array_key_exists(0, $segments)) {
            $controller = \ucfirst($segments[0]);
        }
        
        // Extract action when available
        $action = $this->request->getMethod();
        if (\array_key_exists(1, $segments)) {
            $action = \ucfirst($segments[1]) . 'Action';
        } else {
            $action .= 'IndexAction';
        }
        
        try {
        
            // Assert controller class exists
            $controllerClass = '\\ITC\\Presentation\\Controllers\\'
                . $controllerName;
            
            if (!\class_exists($controllerClass)) {
                
                // Invalid controller
                throw new \InvalidArgumentException(
                    'Invalid controller: ' . $controllerClass
                );
                
            }

            // Init controller class
            $controller = new $controllerClass($this->request, $this->response);
            
            // Assert action is available
            if (!\method_exists($controller, $action)) {
                
                // Invalid action
                throw new \InvalidArgumentException(
                    'Invalid action:' . $controllerClass . '::' . $action
                );
                
            }
            
            // Call action on controller
            $controller->{$action}();
        
            // Send response
            return $this->response->send();
            
        } catch (\InvalidArgumentException $e) {
            
            // Invalid controller or action selected
            return $this->response->sendNotFound();
            
        } catch (\Exception $e) {
            
            // Something out of the frontcontroller scope went wrong
            return $this->response->sendInternalServerError($e);
            
        }
        
    }
    
}
