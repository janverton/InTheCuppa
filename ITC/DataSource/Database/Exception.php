<?php

namespace ITC\DataSource\Database;

class Exception extends \Exception
{
    
    /**
     * Query log
     * 
     * @var array
     */
    protected $queryLog = array();
    
    /**
     * Database exception takes an extra parameter for displaying the query
     * log data to see what went wrong
     * 
     * @param type $message
     * @param type $code
     * @param \Exception $previous
     * @param array $queryLog
     */
    public function __construct(
        $message = "",
        $code = 0,
        \Exception $previous = null,
        array $queryLog = null
    ) {
        
        // Set query log when available
        if ($queryLog) {
            $this->queryLog = $queryLog;
        }
        
        // Construct regular exception
        parent::__construct($message, $code, $previous);
        
    }
    
    /**
     * Append querylog to regular exception __toString
     * 
     * @return string String representation of the exception
     */
    public function __toString()
    {
        
        // Get regular message
        $toString = parent::__toString() . \PHP_EOL;
        
        // Append query log
        $toString .= \implode(\PHP_EOL, $this->queryLog);
        
        // Return String representation
        return $toString;
        
    }
}