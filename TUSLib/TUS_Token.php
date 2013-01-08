<?php
    class TUS_Token {
        private $lineNumber;
        private $text;
        private $order;
        private $is_identifier, $is_string, $is_number, $is_comment;
        const IDE = 1;
        const NUM = 2;
        const STR = 3;
        const COM = 4;
        const UNK = 0;
        const EOL = "\n";
        
        function __construct($text, $type, $order = -1, $lineNumber = -1){
            $this->text = $text;
            $this->lineNumber = $lineNumber;
            $this->order = $order;
            switch ($type){
                case TUS_Token::IDE : $this->setTokenIdentifier(); break;
                case TUS_Token::NUM : $this->setTokenNumber(); break;
                case TUS_Token::STR : $this->setTokenString(); break;
                case TUS_Token::COM : $this->setTokenComment(); break;
                case TUS_Token::EOL : $this->setTokenEOL(); break;    
                default : $this->resetTokenType();   break;
            }            
        }
        
        function resetTokenType(){
            $this->is_identifier = false;
            $this->is_string = false;
            $this->is_number = false;
            $this->is_comment = false;
        }
        
        function setLineNumber($lineNumber){
            $this->lineNumber = $lineNumber;            
        }
        
        function getLineNumber(){
            return $this->lineNumber;
        }
        
        function setTokenIdentifier(){
            $this->resetTokenType();
            $this->is_identifier = true;
        }
        
        function isIdentifier(){
            return $this->is_identifier;
        }
        
        function setTokenNumber(){
            $this->resetTokenType();
            $this->is_number = true;
        }
        
        function isNumber(){
            return $this->is_number;
        }
        
        function setTokenString(){
            $this->resetTokenType();
            $this->is_string = true;
        }
        
        function isComment(){
            return $this->is_comment;
        }
        
        function setTokenComment(){
            $this->resetTokenType();
            $this->is_comment = true;
        }        
        
        function setTokenEOL(){
            $this->resetTokenType();
            $this->is_identifier = true;
        }
                
        function getText(){
            return $this->text;  
        }
        
        function toString(){
            return $this->text;
        }
        
        function setTokenOrder($i){
            $this->order = $i;
        }
        
        function getArrayObject(){
            $result = new ArrayObject;
            $result->text = $this->text;
            $result->lineNumber = $this->lineNumber;
            $result->tokenOrder = $this->order;
            $result->isIdentifier = $this->is_identifier;
            $result->isNumber = $this->is_number;
            $result->isString = $this->is_string;
            $result->isComment = $this->is_comment;
            return $result;
        }
    }
?>