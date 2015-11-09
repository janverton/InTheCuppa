<?php

namespace ITC\DataSource\Database;

/**
 * Database manager
 */
class Manager
{
    
    /**
     * Database manager instance
     * 
     * @var type 
     */
    private static $instance;
    
    /**
     * Created connections
     * 
     * @var array
     */
    protected $connections = array();
    
    /**
     * Database configurations
     * 
     * @var array
     */
    protected $configuration;
    
    /**
     * Prevent construction
     */
    private function __construct()
    {
    }
    
    /**
     * Prevent cloning
     */
    private function __clone()
    {
    }
    
    /**
     * Get manager instance
     * 
     * @return Manager
     */
    public static function getInstance()
    {
        
        // Check whether manager has been set
        if (!isset(self::$instance)) {
            // Manager not set
            
            // Create new manager and load it's configuration
            $manager = new Manager();
            $manager->loadConfiguration();
            
            // Set manager
            self::$instance = $manager;
            
        }
        
        // Return manager instance
        return self::$instance;
        
    }
    
    /**
     * Get master connection for the given scheme
     * 
     * @param string $scheme Scheme to retrieve a connection for
     * @return Client
     */
    public function getMaster($scheme)
    {
        
        // Append master connection
        $name = \strtolower($scheme) . '_master';
        
        // Check whether connection is already available
        if (!\array_key_exists($name, $this->connections)) {
            // Connection has not been instantiated
            
            // Create new connection based on the requested scheme
            $this->connections[$name] =
                new Client(
                    new \mysqli(
                        $this->configuration[$name . '_host'],
                        $this->configuration[$name . '_username'],
                        $this->configuration[$name . '_password'],
                        $this->configuration[$name . '_database']
                    )
                );
        }
        
        // Return database connection
        return $this->connections[$name];
        
    }
    
    /**
     * Get the last insert ID available for the selected scheme
     * 
     * @param string $scheme Scheme to get last insert ID from
     * @return int
     */
    public function getLastInsertId($scheme)
    {
        
        $stmt = $this->getMaster($scheme)->query('SELECT @insertId AS lastInsertId');
        $lastInsertId = $stmt->fetch_assoc();

        return $lastInsertId['lastInsertId'];
        
    }
    
    /**
     * Load mysql configuration
     * 
     * @return Manager
     */
    protected function loadConfiguration()
    {
        $this->configuration = \parse_ini_file('/etc/itc/mysql.ini');
        return $this;
    }
}
