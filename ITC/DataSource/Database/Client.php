<?php

namespace ITC\DataSource\Database;

class Client
{
    
    /**
     * MySQLi client
     * 
     * @var \mysqli
     */
    protected $mysqliClient;
    
    /**
     * Log with executed queries
     * 
     * @var array
     */
    protected $queryLog = array();
    
    /**
     * Instantiate client based on \mysqli connection
     * 
     * @param \mysqli $mysqliClient
     */
    public function __construct(\mysqli $mysqliClient)
    {
        
        // Set mysqli client
        $this->mysqliClient = $mysqliClient;
        
    }
    
    /**
     * Execute a query
     * 
     * @param type $query
     * @param type $parameters
     * 
     * @throws Exception
     * 
     * @return void Description
     */
    public function query($query, $parameters = array())
    {
        
        // Parameter types
        $types = '';
        
        // Parameter references list
        $references = array();
        
        // Add query to be executed to the log
        $this->buildQuery($query, $parameters);
        
        // Check whether parameters need to be bound to the query
        if (\count($parameters)) {
            // Bind parameters
            
            // Iterate parameters
            foreach ($parameters as $key => $value) {

                // Replace param placeholder
                $query = \str_replace(':' . $key, '?', $query);

                // Add parameter by reference, this is mandatory since PHP 5.3+
                // @see http://php.net/manual/en/mysqli-stmt.bind-param.php#104073
                $references[$key] = &$value;

                // Bind parameter as a string
                $types .= 's';

            }

            // Prepend the parameter types to the parameter array
            \array_unshift($references, $types);

            // Prepare query statment
            $statement = $this->mysqliClient->prepare($query);

            // Bind parameters to query statement
            \call_user_func_array(
                array($statement, 'bind_param'),
                $references
            );
        
        } else {
            // Prepare query without any parameters
            
            // Prepare query statment
            $statement = $this->mysqliClient->prepare($query);
            
        }
        
        // Execute statement
        $querySuccesful = $statement->execute();
        
        // Close connection
        $statement->close();
        
        // Assert query has been executed correctly
        if(!$querySuccesful) {
            throw new Exception('Invalid query executed');
        }
        
    }
    
    /**
     * Execute a query and return the results
     * 
     * <code>SELECT `name` FROM USER WHERE `userId` = :userId</code>
     * 
     * <code>array('userId' => 1)</code>
     * 
     * @param string $query      Query string
     * @param array  $parameters Parameters to bind
     * @return array
     */
    public function fetchAll($query, array $parameters)
    {
        
        // Build select query
        $selectQuery = $this->buildQuery($query, $parameters);
        
        // Execute query
        $result = $this->mysqliClient->query($selectQuery);
        /* @var $result \mysqli_result */
        
        $rows = array();
        
        // Get resulting rows as an associative array
        while($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        
        // Free result set
        $result->free();
        
        // Return acquired rows
        return $rows;
        
    }
    
    public function fetchOne($query, $parameters)
    {
        
        // Execute query by using fetchAll
        $rows = $this->fetchAll($query, $parameters);
        
        $numberOfRows = \count($rows);
        
        // Assert exactly one result is returned
        if (1 !== $numberOfRows) {
            // More or less than one row has been found
            
            // Query failed
            throw new Exception(
                'Invalid number of rows for fetchOne: ' . $numberOfRows,
                0,
                null,
                $this->getQueryLog()
            );
            
        }
        
        // Return result
        return $rows[0];
        
    }
    
    /**
     * Return executed queries
     * 
     * @return array
     */
    public function getQueryLog()
    {
        return $this->queryLog;
    }
    
    /**
     * Build query
     * 
     * The parameters which need to be replaced should match the key from the
     * parameter list (with an additional ':' prepended). The value will be
     * escaped as being a float or string
     * 
     * For example, the following query and parameter set will result in 
     * a valid query
     * 
     * <code>SELECT `name` FROM USER WHERE `userId` = :userId</code>
     * 
     * <code>array('userId' => 1)</code>
     * 
     * @param string $query
     * @param array  $parameters
     * @return string Query 
     */
    protected function buildQuery($query, array $parameters)
    {
        
        // Iterate the given parameters
        foreach ($parameters as $key => $value) {
            
            // Escape value
            if (\is_numeric($value)) {
                
                // Use float to cast strings to numbers
                $escapedValue = (float) $value;
                
            } else {
                
                // Escape string value
                $escapedValue = $this->mysqliClient->real_escape_string($value);
                
            }
            
            // Replace placeholder
            $query = \str_replace(':' . $key, $escapedValue, $query);
            
        }
        
        // Add query to be executed to the log
        $this->queryLog[] = $query;
        
        // Return created query
        return $query;
        
    }
    
}