<?php
class NegativeExpr extends TUS_ASTList{
    function operand(){
        return $this->child(0);
    }
    function toString(){
        return "(-".$this->operand()->toString().")";
    }
    
    function evaluate($env){
        $neg = $this->operand()->evaluate($env);
        if ($neg) {
            return (-1)*$neg;            
        } else {
            throw new Exception("Negative Expression Error !");
        }
    }
}

class PrimaryExpr extends TUS_ASTList{
    function create($c){
        if (count($c) == 1) return $c[0];
        else return (new PrimaryExpr($c));
    }
}

class BlockStmnt extends TUS_ASTList{
    function evaluate ($env){
        foreach ($this->children() as $child){
            $result = $child->evaluate($env);
        }
        return $result;
    }
}

class IfStmnt extends TUS_ASTList{
    function condition() {
        return $this->child(0);
    }
    
    function thenBlock() {
        return $this->child(1);
    }
    
    function elseBlock(){
        return $this->numChildren() > 2 ? $this->child(2) : null;
    }
    
    function toString(){
        $s = "(if {$this->condition()->toString()} {$this->thenBlock()->toString()}";
        $elseBlock = $this->elseBlock();
        if ($elseBlock != null) {
            $s .= " else {$this->elseBlock()->toString()})";
        }
        else $s .= ")";        
        return $s;
    }
    
    function evaluate($env){
        $con = $this->condition()->evaluate($env);
        if ($con) {
            return $this->thenBlock()->evaluate($env);
        } else {
            if ($this->elseBlock() != null) {
                return $this->elseBlock()->evaluate($env);
            } else return null;
        }
    }
}

class whileStmnt extends TUS_ASTList{
    function condition() {
        return $this->child(0);
    }
    
    function body() {
        return $this->child(1);
    }
    
    function toString(){
        return "(while {$this->condition()->toString()} {$this->body()->toString()})";
    }
    
    function evaluate($env){
        $result = null;
        while (true){
            $con = $this->condition()->evaluate($env);
            if ($con) {
                $result = $this->body()->evaluate($env);
            } else {
                return $result;
            }
        }
    }
}

class NullStmnt extends TUS_ASTList{
    function toString(){
        return "";
    }
    function evaluate($env){
        return null;
    }
}

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
            return new NegativeExpr(array($this->primary()));
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
        return new BlockStmnt($statements);                
    }
    
    function simple(){
        return $this->equation();
    }
    
    function statement(){
        if ($this->isToken('if')) {
            $this->token('if');
            $e = $this->equation();
            $b1 = $this->block();
            $i = new IfStmnt(array($e,$b1));
            if ($this->isToken('else')){
                $this->token("else");
                $b2 = $this->block();
                $i = new IfStmnt(array($e,$b1,$b2));
            }
            return $i;
        } else if ($this->isToken('while')) {
            $this->token("while");
            $e = $this->equation();
            $b1 = $this->block();
            return new whileStmnt(array($e, $b1));                        
        } else {
            return $this->simple();
        }
    }
    
    function program(){        
        if ($this->isToken(";") || $this->isToken(TUS_Token::EOL)){            
            $n = $this->file->read();            
            return new NullStmnt();
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
            $this->program()->evaluate($env);
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