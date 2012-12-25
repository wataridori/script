<?php
class TUS_BuiltinFunction {    
    static $listFunction = array ("print","time");
    
    static function run ($funcname,$args) {        
        return call_user_func("TUS_BuiltinFunction::TUS_".$funcname,$args);        
    }
    
    function TUS_print($args) {        
        foreach ($args as $arg)
            echo $arg."\n";
        return null;
    }
    
    function TUS_time() {                
        return microtime(true);
    }
}
?>