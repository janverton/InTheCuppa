<?php

namespace ITC\DataSource\Model;

class AbstractModel
{
    
    /**
     * Model data set
     * 
     * @var array
     */
    protected $data = array();
    
    /**
     * Whether or not the model properties have been changed
     * 
     * @var bool
     */
    protected $isDirty = false;
    
    /**
     * Fields which should not be exposed to the outside world (like 
     * password/salt for example)
     * 
     * @var array
     */
    protected $hiddenFields = array();
    
    /**
     * Set model data. If set when model has been changed already, the
     * changes will be overwritten and the dirty state will be reverted
     * 
     * @param array $data
     * 
     * @return AbstractModel
     */
    public function setData(array $data)
    {
        
        // Mark object clean
        $this->isDirty = false;
        
        // Set data set
        $this->data = $data;
        
        // Implement fluent interface
        return $this;
        
    }
    
    /**
     * Set model property. This will mark the model as dirty.
     * 
     * @param string $property
     * @param mixed  $value
     * 
     * @return AbstractModel
     */
    public function __set($property, $value)
    {
        
        // Mark model dirty
        $this->isDirty = true;
        
        // @todo allowed properties should be whitelisted and type checked
        
        // Set property
        $this->data[$property] = $value;
        
        // Implement fluent interface
        return $this;
        
    }
    
    /**
     * Get property from the data set
     * 
     * @param string $property
     * 
     * @return mixed
     * 
     * @throws \DomainException
     */
    public function &__get($property)
    {
        
        // Assert property exists
        if (!\array_key_exists($property, $this->data)) {
            // Property does not exist
            
            // Throw a domain exception
            throw new \DomainException(
                'Invalid ' . __CLASS__ . 'property \'' . $property . '\' defined'
            );
            
        }
        
        // Return requested property
        return $this->data[$property];
        
    }
    
    /**
     * Export model data to an array. Note that hidden fields will not be
     * returned
     * 
     * @return array
     */
    public function toArray()
    {
        
        // @todo exclude hidden fields from the data set
        
        // Return data
        return $this->data;
        
    }
    
    /**
     * Return whether or not the model properties have been changed
     * 
     * @return bool
     */
    public function isDirty()
    {
        return $this->isDirty;
    }
    
}
