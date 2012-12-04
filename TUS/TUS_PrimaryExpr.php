<?php
class TUS_PrimaryExpr extends TUS_ASTList{
    function create($c){
        if (count($c) == 1) return $c[0];
        else return (new TUS_PrimaryExpr($c));
    }
    
    function hasPostFix(){
        return $this->numChildren()>1;
    }
    
    function evaluate($env) {        
        $operand = $this->child(0)->evaluate($env);        
        if ($this->hasPostFix()) {
            $postfix = $this->child(1);                    
            if (!($operand->parameters() && $operand->body())) {                
            }
            $parameters = $operand->parameters();            
            $nestEnv = new TUS_BasicEnv();
            for ($i =0 ; $i<$parameters->numChildren(); $i++) {                
                $nestEnv->put($parameters->child($i)->toString(), $postfix->child($i)->evaluate($env));                
            }            
            return $operand->body()->evaluate($nestEnv);
        } else {
            return $operand;
        }
    }
}
?>