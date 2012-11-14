<?php
class TUS_ExprParser{
    private $file;
    function __construct($file){
        $this->file = $file;        
    }
    
    function expression(){
        $left = $this->term();
        while ($this->isToken("+") || $this->isToken("-")) {
            $op = new TUS_ASTLeaf($this->file->read());
            $right = $this->term();
            $left = new TUS_BinaryExpr(array($left,$op,$right));
        }
        return $left;
    }
    
    function term() {
        $left = $this->factor();
        while ($this->isToken("*") || $this->isToken("/")) {
            $op = new TUS_ASTLeaf($this->file->read());
            $right = $this->factor();
            $left = new TUS_BinaryExpr(array($left,$op,$right));
        }
        return $left;
    }
    
    function factor(){
        if ($this->isToken("(")){
            $this->token("(");
            $e = $this->expression();
            $this->token(")");
            return $e;
        }
        else {
            $t = $this->file->read();
            if ($t->isNumber()) return (new TUS_ASTLeaf($t));
            else exit();
        }        
    }
    
    function token($name) {
        $t = $this->file->read();
        if (!($t->isIdentifier() && $t->getText() == $name))
            exit();
    }
    
    function isToken($name){
        if (!$this->file->hasMore()) return false;
        $t = $this->file->getCurrentToken();        
        return ($t->isIdentifier() && $t->getText() == $name);
    }
}