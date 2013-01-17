<?php
class TUS_ASTLeaf{
    protected $token;
    
    function __construct($token){
        $this->token = $token;
    }
    
    function numChildren(){
        return 0;
    }
    
    function children(){
        return null;
    }
    
    function toString() {
        return $this->token->getText();
    }
    
    function location(){
        return $this->token->getLineNumber();
    }
    
    function token(){
        return $this->token;
    }
    
    function evaluate($env){
        return $this->token->getText();
    }
}
?>