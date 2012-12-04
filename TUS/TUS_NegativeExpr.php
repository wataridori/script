<?php
class TUS_NegativeExpr extends TUS_ASTList{
    function operand(){
        return $this->child(0);
    }
    function toString(){
        return "(-".$this->operand()->toString().")";
    }
    
    function evaluate($env){
        $neg = $this->operand()->evaluate($env);
        if ($neg) {
            return -$neg;            
        } else {
            throw new Exception("Negative Expression Error !");
        }
    }
}
?>