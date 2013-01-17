<?php
class TUS_Name extends TUS_ASTLeaf{        
    function evaluate($env) {
        return $env->get($this->toString());
    }
}
?>