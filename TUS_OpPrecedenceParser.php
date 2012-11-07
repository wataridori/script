<?php

class Precedence {
    public $value;
    public $leftAssoc;
    function __construct($v, $a){
        $this->value = $v;
        $this->leftAssoc = $a;
    }
}

class TUS_OpPrecedenceParser{
    private $file;
    private $operators;
    
    function __construct($file){
        $this->file = $file;
        $operators = new ArrayObject;
        $operators["<"] = new Precedence(1,true);
        $operators[">"] = new Precedence(1,true);
        $operators["+"] = new Precedence(2,true);
        $operators["-"] = new Precedence(2,true);
        $operators["*"] = new Precedence(3,true);
        $operators["/"] = new Precedence(3,true);
        $operators["^"] = new Precedence(4,true);
        $this->operators = $operators;
    }
    
    function expression() {
        $right = $this->factor();
        while (($next = $this->nextOperator()) != null)
            $right = $this->doShift($right,$next->value);
        return $right;
    }
    
    function doShift($left, $prec){
        $op = $this->file->read();
        $right = $this->factor();
        while (($next = $this->nextOperator()) != null && $this->rightIsExpr($prex,$next))
            $right = $this->doShift($right,$next->value);
        return new TUS_BinaryExpr(array($left,$op,$right));
    }
    
    function nextOperator(){
        if (!$this->file->hasMore()) return null;
        $t = $this->file->getCurrentToken();
        if ($t->isIdentifier())
            return $this->operators["{$t->getText()}"];
        else return null;
    }
    
    function rightIsExpr($prec,$nextPrec){
        if ($nextPrec->leftAssoc) {
            return $prec < $nextPrec->value;
        }
        else return $prec <= $nextPrec->value;
    }
    
    function factor(){
        if ($this->isToken("(")){
            $this->token("(");
            $e = $this->expression();
            $this->token(")");
            return $e;
        }
         else {
            if ($this->file->hasMore()){
                $t = $this->file->read();
                if ($t->isNumber()) return (new TUS_ASTLeaf($t));
                else exit();
            }
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