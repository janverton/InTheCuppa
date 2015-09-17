<?php

namespace ITC\Presentation\Http;

/**
 * The Response class defines which headers to send as well as constructing
 * a response body
 */
class Response
{
    
    // Define response headers
    const OK = '200 OK';
    const BAD_REQUEST = '400 Bad Request';
    const FORBIDDEN = '403 Forbidden';
    const NOT_FOUND = '404 Not Found';
    const INTERNAL_SERVER_ERROR = '500 Internal Server Error';
    
    /**
     * Response body
     * 
     * @var string
     */
    protected $body = '';
    
    /**
     * Current response header
     * 
     * @var string
     */
    protected $statusCode = self::OK;
    
    /**
     * Whether or not to display debug information
     * 
     * @var bool
     */
    protected $debug = false;
    
    /**
     * Set response headers and return JSON encoded body data
     * 
     * For turning off server signature headers see
     * http://ask.xmodulo.com/turn-off-server-signature-apache-web-server.html
     * 
     * @return string JSON encoded response body
     */
    public function send()
    {

        // Set Response header
        \header('HTTP/1.1 ' . $this->statusCode, true);
        \header('Host: ' . $_SERVER['HTTP_HOST'] . ':80');
        \header('Content-Type: application/json');
        \header('Content-Length: ' . \strlen($this->body));
        \header('Accept: application/json');
        
        return $this->body;
        
    }
    
    /**
     * Set body data which is send in the response. Body will be encoded to
     * JSON formatting
     * 
     * @param mixed $body Body data
     * @return Response
     */
    public function setBody($body)
    {
        $this->body = \json_encode($body);
        return $this;
    }
    
    /**
     * Resource not found
     * 
     * Send 404 repsonse
     * @return string
     */
    public function sendNotFound()
    {
        $this->statusCode = self::NOT_FOUND;
        return $this->send();
    }
    
    /**
     * Not allowed to access resource
     * 
     * @return string
     */
    public function sendForbidden()
    {
        $this->statusCode = self::FORBIDDEN;
        return $this->send();
    }
    
    /**
     * Internal server error
     * 
     * @param \Exception $exception
     * @return string
     */
    public function sendInternalServerError($exception)
    {
        
        $this->statusCode = self::INTERNAL_SERVER_ERROR;
        
        // Add debug info when in debugging mode
        if ($this->debug) {
            $this->body = \json_encode(
                array(
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTrace()
                )
            );
        }
        
        return $this->send();
    }
    
    /**
     * Enable or disable debugging
     * 
     * @param bool $enabled
     * return Response
     */
    public function enableDebug($enabled = true)
    {
        $this->debug = (bool) $enabled;
        return $this;
    }
}
