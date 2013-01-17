<?php
class TUS_ASTList{
    protected $children;
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
?>