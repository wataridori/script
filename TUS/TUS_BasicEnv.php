<?php
class TUS_BasicEnv{
    protected $values;
    protected $parentEnv = null;
    function __construct($defaultValue = null){
        if ($defaultValue == null) $defaultValue = new ArrayObject;
        $this->values = $defaultValue;
    }
    
    function setParentEnv($env){
        $this->parentEnv = $env;
    }
    
    function hasParentEnv(){
        if ($this->parentEnv == null) return false;
        else return true;
    }
    
    function getParentEnv(){
        return $this->parentEnv;
    }
    
    function put($name,$value){
        $this->values[$name] = $value;
    }
    
    function get($name) {        
        if (array_key_exists($name,$this->values))
            return $this->values[$name];
        else if ($this->hasParentEnv()){            
            return $this->getParentEnv()->get($name);        
        }
        else {            
            return null;
        }        
    }    
}
?>