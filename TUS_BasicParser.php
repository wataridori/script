<?php
class NegativeExpr extends TUS_ASTList{
    function operand(){
        return $this->child(0);
    }
    function toString(){
        return "-".$this->operand();
    }
}

class PrimaryExpr extends TUS_ASTList{
    function create($c){
        if (count($c) == 1) return $c[0];
        else return (new TUS_PrimaryExpr($c));
    }
}

class BlockStmnt extends TUS_ASTList{
    
}

class IfStmnt extends TUS_ASTList{
    function condition() {
        return $this->child(0);
    }
    
    function thenBlock() {
        return $this->child(1);
    }
    
    function elseBlock(){
        return $this->numChildren > 2 ? $this->child(2) : null;
    }
    
    function toString(){
        return "(if" + $this->condition() + " " + $this->thenBlock() + " else " + $this->elseBlock() + ")";
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
        return "(while " + $this->condition() + " " + $this->body() + ")";
    }
}

class NullStmnt extends TUS_ASTList{
    
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
            if ($t->isNumber() || $t->isIdentifier() || $t->isString()) return $t;            
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
        while ($this->isToken("*") || $this->isToken("/") || $this->isToken("%")){
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
                exit;
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
        
    }
}