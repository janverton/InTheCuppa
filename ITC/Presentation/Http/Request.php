<?php

namespace ITC\Presentation\Http;

class Request
{
    
    /**
     * Request method (get|put|post|delete)
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
     * Instantiating a request will parse the request method and url segments
     */
    public function __construct()
    {
        
        // Parse request method
        $this->parseMethod()
        
            // Parse url parameter to key value pairs
            ->parseUrlSegments();
        
    }
    
    /**
     * Get current request method (get|put|post|delete)
     * 
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
    
    public function getParam($name, $default = false)
    {
        if (\array_key_exists($name, $_REQUEST)) {
            return $_REQUEST[$name];
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
        
        // Split url into segments
        $segments = \explode('/', \trim($baseUrl, '//'));

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
    
    /**
     * Parse request method
     * 
     * @return Request
     */
    protected function parseMethod()
    {
        
        // Only overwrite request method when it is set correctly
        if (\array_key_exists('REQUEST_METHOD', $_SERVER)) {
            $method = \strtolower($_SERVER['REQUEST_METHOD']);
            if (\in_array($method, array('put', 'post', 'get', 'delete'))) {
                $this->method = $method;
            }
        }
        
        return $this;
        
    }
    
}
