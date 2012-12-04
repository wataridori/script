<?php
class TUS_BlockStmnt extends TUS_ASTList{
    function evaluate ($env){
        foreach ($this->children() as $child){
            $result = $child->evaluate($env);
        }
        return $result;
    }
}

class TUS_IfStmnt extends TUS_ASTList{
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

class TUS_whileStmnt extends TUS_ASTList{
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

class TUS_NullStmnt extends TUS_ASTList{
    function toString(){
        return "";
    }
    function evaluate($env){
        return null;
    }
}

class TUS_DefStmnt extends TUS_ASTList{
    function name (){            
        return $this->child(0);
    }
    
    function parameters() {
        return $this->child(1);
    }
    
    function body () {
        return $this->child(2);
    }
    
    function toString() {
        return "(def {$this->name()->toString()} {$this->parameters()->toString()} {$this->body()->toString()})";
    }
    
    function evaluate($env) {
        $func = new TUS_Function($this->parameters(),$this->body(),$env);            
        $env->put($this->name()->toString(), $func);            
        return $func;
    }
}
?>