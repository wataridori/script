<?php
    class TUS_PostFix extends TUS_ASTList{
        function __construct($list = null) {
            $this->children = $list;
        }
    }
    
    class TUS_Arguments extends TUS_PostFix {
        function size() {
            return $this->numChildren();
        }        
    }        
    
    class TUS_Function{
        protected $params, $body, $env;
        function __construct($params, $body, $env) {
            $this->params = $params;
            $this->body = $body;
            $this->env = $env;
        }
                
        function parameters(){
            return $this->params;
        }
        
        function body(){
            return $this->body;
        }
    }
    
    class TUS_FuncParser extends TUS_BasicParser{
        private $definedFunc = array();
        function __construct($file){            
            $this->file = $file;            
        }
        
        function param(){
            $p = $this->file->read();
            if ($p->isIdentifier()) {
                return new TUS_Name($p);
            } else $this->throwError($p);
        }
        
        function params(){            
            $left = $this->param();
            $params = array($left);
            while ($this->isToken(",")){
                $this->token(",");
                $params[] = $this->param();                
            }            
            return new TUS_Arguments($params);
        }
        
        function paramList(){
            $this->token ("(");
            if ($this->isToken(")")) {                
                $p = new TUS_NullStmnt();
            } else {                
                $p = $this->params();
            }
            $this->token(")");
            return $p;
        }
        
        function funcDef(){
            $this->token("def");
            $name = $this->file->read();
            $this->definedFunc[] = $name->toString();
            $funcName = new TUS_Name($name);            
            $funcParamList = $this->paramList();            
            $funcBody = $this->block();
            return new TUS_DefStmnt(array($funcName,$funcParamList,$funcBody));
        }
        
        function args() {
            $left = $this->equation();
            $args = array($left);            
            while ($this->isToken(",")) {
                $this->token(",");
                $args[] = $this->equation();                
            }            
            return new TUS_ASTList($args);
        }
        
        function postfix(){
            $this->token("(");
            if ($this->isToken(")")) {
                $arg = new TUS_NullStmnt();
            } else {
                $arg = $this->args();
            }
            $this->token(")");
            return $arg;
        }
        
        function primary(){
            if ($this->isToken("(")) {
                $this->token("(");
                $left = $this->equation();
                $this->token(")");
            } else {
                $t = $this->file->read();
                if ($t->isIdentifier()) {
                    $left = new TUS_Name($t);
                } else if ($t->isNumber() || $t->isString()) {
                    $left = new TUS_ASTLeaf($t);
                } else {
                    $this->throwError($t);
                }
            }
            
            if ($this->isToken("(")) {                
                if (!in_array($left->toString(),$this->definedFunc)) {
                    $this->throwError($left->token());                    
                }
                $right = $this->postfix();                
                return new TUS_PrimaryExpr(array ($left,$right));
            } else {
                return new TUS_PrimaryExpr(array($left));
            }
        }
        
        function throwError($token) {
            echo "Error occured at line {$token->getLineNumber()}. Function {$token->getText()} undefined !\n";
            exit();
        }
        
        function simple(){
            $left = $this->equation();
            if ($this->isToken("(")) {                
                $right = $this->postfix();
                $left = new TUS_ASTList(array($left,$right));
            }
            return $left;
        }
        
        function program(){            
            if ($this->isToken(";") || $this->isToken(TUS_Token::EOL)) {                
                $this->file->read();
                return new TUS_NullStmnt();
            } else {                
                if ($this->isToken("def")) {                    
                    $fd = $this->funcDef();                    
                    $this->file->read();                    
                    return $fd;
                } else {                    
                    $stmn = $this->statement();
                    $this->file->read();
                    return $stmn;
                }
            }
        }                
    }        
?>