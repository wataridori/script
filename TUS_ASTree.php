<?php
class TUS_ASTLeaf{
    private $token;
    
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

class TUS_Name extends TUS_ASTLeaf{        
    function evaluate($env) {
        return $env->get($this->name());
    }
}

class TUS_ASTList{
    private $children;
    function __construct($list = null) {
        $this->children = $list;
    }
    
    function child($i){
        return $this->children[$i];
    }
    
    function numChildren(){
        return count($this->children);
    }
    
    function children(){
        return $this->children;
    }                
    
    function location(){
        foreach ($this->children as $child) {
            return $child->location();
        }
    }
    
    function toString ($list = null){
        $s = "(";
        $sep ="";
        if ($list == null) $list = $this->children();
        foreach ($list as $child){            
            $s .= $sep;
            $sep = " ";
            if (get_class($child) == "TUS_ASTLeaf")
                $s .= $child->toString();
            else 
                $s .= $child->toString($child->children());
        }
        $s .=")";
        return $s;
    }
}

class TUS_BinaryExpr extends TUS_ASTList{
    function left(){
        return $this->child(0);
    }
    
    function operator(){
        return ($this->child(1)->token()->getText());
    }
    
    function right(){
        return $this->child(2);
    }
    
    function evaluate($env){
        $op = $this->operator();
        
        if ($op == "="){
            $rightValue = $this->right()->evaluate($env);
            if ($this->left()->token()->isIdentifier()){
                $env->put($this->left()->toString(),$rightValue);
            }
            return $rightValue;
        } else if ($op == "+") {
            return $this->left()->evaluate($env) + $this->right()->evaluate($env);
        } else if ($op == "-") {
            return $this->left()->evaluate($env) - $this->right()->evaluate($env);
        } else if ($op == "*") {
            return $this->left()->evaluate($env) * $this->right()->evaluate($env);
        } else if ($op == "/") {
            return $this->left()->evaluate($env) / $this->right()->evaluate($env);
        } else if ($op == "%") {
            return $this->left()->evaluate($env) % $this->right()->evaluate($env);
        } else if ($op == "^") {
            return pow($this->left()->evaluate($env),$this->right()->evaluate($env));
        } else if ($op == "==") {
            return $this->left()->evaluate($env) == $this->right()->evaluate($env);
        } else if ($op == ">") {
            return $this->left()->evaluate($env) > $this->right()->evaluate($env);
        } else if ($op == "<") {
            return $this->left()->evaluate($env) < $this->right()->evaluate($env);
        }
        
        return null;
    }    
}