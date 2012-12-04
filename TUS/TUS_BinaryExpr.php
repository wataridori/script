<?php
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
            if (get_class($this->left()) == "TUS_PrimaryExpr") $leftToken = $this->left()->child(0)->token();
            else $leftToken = $this->left()->token();                    
            if ($leftToken->isIdentifier()){                
                $env->put($leftToken->toString(),$rightValue);                
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
?>