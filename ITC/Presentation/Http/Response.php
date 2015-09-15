<?php

namespace ITC\Presentation\Http;

class Response
{
    
    const OK = '200 OK';
    const BAD_REQUEST = '400 Bad Request';
    const FORBIDDEN = '403 Forbidden';
    const NOT_FOUND = '404 Not Found';
    const INTERNAL_SERVER_ERROR = '500 Internal Server Error';
    
    protected $body = '';
    protected $statusCode = self::OK;
    protected $debug = false;
    
    public function send()
    {
        
        \ob_clean();
        
        // Only add headers when not set
        if (!\count(\headers_list())) {
            \header('HTTP/1.1 ' . $this->statusCode, true);
        }
        
        echo \json_encode($this->body);
        exit;
        
    }
    
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }
    
    public function sendNotFound()
    {
        $this->statusCode = self::NOT_FOUND;
        $this->send();
    }
    
    public function sendForbidden()
    {
        $this->statusCode = self::FORBIDDEN;
        $this->send();
    }
    
    public function sendInternalServerError($message = '', $trace = '')
    {
        $this->statusCode = self::INTERNAL_SERVER_ERROR;
        $this->send();
    }
    
    public function enableDebug($enabled = true)
    {
        $this->debug = (bool) $enabled;
    }
    
    public function forward($url)
    {
        \header('Location: http://' . $_SERVER['HTTP_HOST'] . '/' . $url);
        return $this;
    }
}
