<?php

namespace ITC\Presentation\Http;

/**
 * The request instance is responsible for retrieving all request data and
 * converting it into url segments, request parameters and body data
 * (get|post|put|patch|delete)
 */
class Request
{
    
    /**
     * Request method (get|post|put|patch|delete)
     * 
     * @var string
     */
    protected $method = 'get';
    
    /**
     * Parsed url segments
     * 
     * @var array
     */
    protected $segments = array();
    
    /**
     * Available paramaters
     * 
     * @var array
     */
    protected $param = array();
    
    /**
     * Instantiating a request will parse the request method and url segments
     */
    public function __construct($method, $paramData)
    {
        
        // Set request method
        $this->setMethod($method);
        
        // Set param data
        $this->param = $paramData;
        
        // Parse request method
        $this->parseUrlSegments();
        
    }
    
    /**
     * Set request method (get|post|put|patch|delete)
     * 
     * @param string $method
     * @throws \BadMethodCallException
     */
    protected function setMethod($method)
    {
        
        // Assert method is available
        if (\in_array($method, array('get', 'post', 'put', 'patch', 'delete'))) {
            $this->method = $method;
        } else {
            throw new \BadMethodCallException(
                'Invalid request method: ' . $method
            );
        }
        
        return $this;
    }
    
    /**
     * Get current request method (get|post|put|patch|delete)
     * 
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
    
    /**
     * Get param from param data
     * 
     * @param string $name    Get parameter by name
     * @param mixed  $default 
     * @return mixed
     */
    public function getParam($name, $default = false)
    {
        
        // Return parameter when it exists
        if (\array_key_exists($name, $this->param)) {
            return $this->param[$name];
        }
        return $default;
    }
    
    /**
     * Get segment value
     * 
     * For example, this url:<br>
     * <code>/season/1</code>
     * 
     * will return:<br>
     * <code>
     * $request->getSegment('season'); // 1
     * </code>
     * 
     * @param string $key The segment key to retrieve
     * @return int
     */
    public function getSegment($key)
    {
        
        // Return url value
        if (\array_key_exists($key, $this->segments)) {
            return $this->segments[$key];
        }
        
    }
    
    /**
     * Get the available segment keys
     * 
     * For example, this url:<br>
     * <code>/season/1/winner/2</code>
     * 
     * will return:<br>
     * <code>
     * array('season', 'winner');
     * </code>
     * 
     * @return array
     */
    public function getSegmentKeys()
    {
        return \array_keys($this->segments);
    }
    
    /**
     * Parse the url segments into a segment key/value pair structure
     * 
     * For example, this url:<br>
     * <code>/season/1/winner/2?debug=on</code>
     * 
     * is internally translated to:<br>
     * <code>
     * array(
     *  'season' => 1,
     *  'winner' => 2
     * );
     * </code>
     * 
     * @return Request
     */
    protected function parseUrlSegments()
    {
       
        $url = $this->getParam('url', '');
        
        // Get url query position (starts at the '?')
        $urlQueryPosition = \strpos($url, '?');
        
        // Check whether url has a query
        if ($urlQueryPosition) {
            
            // Extract base url
            $baseUrl = \substr($url, 0, $urlQueryPosition);
            
        } else {
            
            // Url has no query
            $baseUrl = $url;
            
        }
        
        // Remove leading or ending slashes from base url
        $strippedBaseUrl = \trim($baseUrl, '/');

        // Define segments list
        $segments = array();
        
        // Check whether at least one segment is available
        if (0 < \strlen($strippedBaseUrl)) {
            // 1 or more segments are set
            
            // Split url into segments
            $segments = \explode('/', $strippedBaseUrl);
            
        }
        
        $numberOfSegments = \count($segments);
        $key = false;
        
        // Iterate url segments
        for ($i = 0; $i < $numberOfSegments; $i++) {
            
            // Get current segment
            $segment = $segments[$i];
            
            // Check whether this is the numeric part of the segment
            if ($key && \is_numeric($segment)) {
                
                // Add segment and reset last assigned key
                $this->segments[$key] = (int) $segment;
                $key = false;
                
            } else {
                
                // Add segment and set it as the last assigned key
                $this->segments[$segment] = false;
                $key = $segment;
                
            }
            
        }
        
        return $this;
        
    }
}
