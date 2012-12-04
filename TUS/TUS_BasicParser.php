<?php
class TUS_BasicParser {
    protected $file;
    function __construct($file)   {        
        $this->file = $file;        
    }
    
    function primary(){
        if ($this->isToken('(')) {
            $this->token('(');
            $e = $this->equation();
            $this->token(')');
            return $e;
        } else {
            $t = $this->file->read();
            if ($t->isIdentifier()) return new TUS_Name($t);
            if ($t->isNumber() || $t->isString()) return new TUS_ASTLeaf($t);
            else $this->throwError($t);
        }        
    }
    
    function factor(){
        if ($this->isToken("-")){
            $op = new TUS_ASTLeaf($this->file->read());
            return new TUS_NegativeExpr(array($this->primary()));
        }
        else return $this->primary();
    }
    
    function term() {
        $left = $this->factor();
        while ($this->isToken("*") || $this->isToken("/") || $this->isToken("%") || $this->isToken("^")){
            $op = new TUS_ASTLeaf($this->file->read());
            $right = $this->factor();
            $left = new TUS_BinaryExpr(array($left,$op,$right));
        }
        return $left;
    }
    
    function expr(){
        $left = $this->term();
        while ($this->isToken("+") || $this->isToken("-")) {
            $op = new TUS_ASTLeaf($this->file->read());
            $right = $this->term();
            $left = new TUS_BinaryExpr(array($left,$op,$right));
        }
        return $left;
    }
    
    function inequality(){
        $left = $this->expr();
        while ($this->isToken("==") || $this->isToken(">") || $this->isToken("<")) {
            $op = new TUS_ASTLeaf($this->file->read());
            $right = $this->expr();
            $left = new TUS_BinaryExpr(array($left,$op,$right));
        }
        return $left;
    }
    
    function equation(){
        $left = $this->inequality();
        while ($this->isToken("=")){
            $op = new TUS_ASTLeaf($this->file->read());
            $right = $this->equation();
            $left = new TUS_BinaryExpr(array($left,$op,$right));
        }
        return $left;
    }
    
    function block(){
        $statements = array();
        $this->token("{");
        if (!($this->isToken(";") || $this->isToken(TUS_Token::EOL))){
            $statements[] = $this->statement();
        }
        while (!$this->isToken("}")){
            if (!($this->isToken(";") || $this->isToken(TUS_Token::EOL))){
                $this->throwError($this->file->read());
            }
            $this->file->read();
            if (!($this->isToken(";") || $this->isToken(TUS_Token::EOL) || $this->isToken("}"))){
                $statements[] = $this->statement();
            }
        }
        $this->token("}");
        return new TUS_BlockStmnt($statements);                
    }
    
    function simple(){
        return $this->equation();
    }
    
    function statement(){
        if ($this->isToken('if')) {
            $this->token('if');
            $e = $this->equation();
            $b1 = $this->block();
            $i = new TUS_IfStmnt(array($e,$b1));
            if ($this->isToken('else')){
                $this->token("else");
                $b2 = $this->block();
                $i = new TUS_IfStmnt(array($e,$b1,$b2));
            }
            return $i;
        } else if ($this->isToken('while')) {
            $this->token("while");
            $e = $this->equation();
            $b1 = $this->block();
            return new TUS_whileStmnt(array($e, $b1));                        
        } else {
            return $this->simple();
        }
    }
    
    function program(){        
        if ($this->isToken(";") || $this->isToken(TUS_Token::EOL)){            
            $n = $this->file->read();            
            return new TUS_NullStmnt();
        } else {            
            $s = $this->statement();
            $n = $this->file->read();            
            return $s;
        }
    }
    
    function parse(){
        $s = "";
        while ($this->file->hasMore()){
            $p = $this->program();            
            $s .= $p->toString()."\n";
        }
        return $s;
    }
    
    function evaluate($env){        
        while ($this->file->hasMore()){            
            $p = $this->program();            
            $p->evaluate($env);            
        }        
    }
    
    function renew(){
        $this->fie->setCurrent(0);
    }
    
    function throwError($token){
        throw new Exception("Error occured at line {$token->getLineNumber()}. Invalid token {$token->getText()}");
    }
    
    function token($name) {        
        $t = $this->file->read();
        if (!($t->isIdentifier() && $t->getText() == $name))
            $this->throwError($t);
    }
    
    function isToken($name){
        if (!$this->file->hasMore()) return false;        
        $t = $this->file->getCurrentToken();        
        return ($t->isIdentifier() && $t->getText() == $name);
    }
}