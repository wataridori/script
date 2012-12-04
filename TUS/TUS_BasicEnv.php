<?php
class TUS_BasicEnv{
    protected $values;
    function __construct($defaultValue = null){
        if ($defaultValue == null) $defaultValue = new ArrayObject;
        $this->values = $defaultValue;
    }
    
    function put($name,$value){
        $this->values[$name] = $value;
    }
    
    function get($name) {
        return $this->values[$name];
    }    
}
?>