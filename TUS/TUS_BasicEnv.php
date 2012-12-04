<?php
class TUS_BasicEnv{
    protected $values;
    protected $pe = null;
    function __construct($defaultValue = null){
        if ($defaultValue == null) $defaultValue = new ArrayObject;
        $this->values = $defaultValue;
    }
    
    function setParentEnv($env){
        $this->pe = $env;
    }
    
    function hasParentEnv(){
        if ($this->pe == null) return false;
        else return true;
    }
    
    function getParentEnv(){
        return $this->pe;
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
        else return null;
    }    
}
?>