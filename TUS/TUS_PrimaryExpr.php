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
        if ($this->hasPostFix()) {
            $funcname = $this->child(0)->toString();            
            if (in_array($funcname,TUS_BuiltinFunction::$listFunction)) {
                $postfix = $this->child(1);
                $args = array();
                for ($i =0 ; $i<$postfix->numChildren(); $i++) {                    
                    $a = $postfix->child($i)->evaluate($env);
                    $args[] = $a;                                   
                }                
                return TUS_BuiltinFunction::run($funcname,$args);                                               
            }
            $operand = $this->child(0)->evaluate($env);            
            $postfix = $this->child(1);                    
            if (!($operand->parameters() && $operand->body())) {                
            }
            $parameters = $operand->parameters();            
            $curEnv = new TUS_BasicEnv();
            for ($i =0 ; $i<$parameters->numChildren(); $i++) {                
                if ($operand->parameters() && $operand->body())
                    $curEnv->put($parameters->child($i)->toString(), $postfix->child($i)->evaluate($env));                
            }
            $curEnv->setParentEnv($env);                                      
            return $operand->body()->evaluate($curEnv);            
        } else {
            $operand = $this->child(0)->evaluate($env);            
            return $operand;
        }
    }
}
?>