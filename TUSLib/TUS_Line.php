<?php    
    class TUS_Line{
        private $lineNumber;
        private $tokenCount = 0;
        private $text;
        private $numReg ="/[0-9]+/";
        private $strReg = '/\"(\\\\\"|\\\\\\\\|\\\\n|[^\"])*\"/';  
        private $idenReg = "/[A-Z_a-z][A-Z_a-z0-9]*|==|<=|>=|&&|\|\||[[:punct:]]/";
        private $tokens;
        private $current = 0;
        
        function __construct($text, $lineNumber){
            $this->text = $text;
            $this->lineNumber = $lineNumber;            
            $reg = "/\\s*|((\/\/.*)|([0-9]+)|(\"(\\\\\"|\\\\\\\\|\\\\n|[^\"])*\")"."|[A-Z_a-z][A-Z_a-z0-9]*|==|<=|>=|&&|\\|\\||[[:punct:]])?/";
            preg_match_all($reg,$this->text,$matches);
            $i = 0;
            foreach ($matches[0] as $match){                
                if (preg_match("/\/\/.*/",$match)){                    
                    $token = new TUS_Token($match,TUS_Token::COM,$i,$this->lineNumber);                                        
                    $this->tokens[$i++] = $token;
                }
                else if (preg_match("/[\s]+/", $match) && preg_match($this->strReg,$match)) {                    
                    $token = new TUS_Token($match,TUS_Token::STR,$i,$this->lineNumber);                        
                    $this->tokens[$i++] = $token;
                }
                else if (!preg_match("/[\s]+/", $match) && $match != ""){                    
                    if (preg_match($this->numReg,$match)){                        
                        $token = new TUS_Token($match,TUS_Token::NUM,$i,$this->lineNumber);                        
                        $this->tokens[$i++] = $token;
                    }                                        
                    else if (preg_match($this->strReg, $match)) {                        
                        $token = new TUS_Token($match,TUS_Token::STR,$i,$this->lineNumber);                        
                        $this->tokens[$i++] = $token;
                    }
                    else if (preg_match($this->idenReg,$match)){
                        $token = new TUS_Token($match,TUS_Token::IDE,$i,$this->lineNumber);                        
                        $this->tokens[$i++] = $token;
                    }                    
                }                
            }
            $this->tokenCount = count($this->tokens);
        }
        
        function setLineNumber($lineNumber){
            $this->lineNumber = $lineNumber;            
        }
        
        function getLineNumber(){
            return $this->lineNumber;
        }            
        
        function getArrayObject(){
            $result = new ArrayObject;
            $result->text = $this->text;
            $result->lineNumber = $this->lineNumber;
            $result->tokenCount = $this->tokenCount;
            $result->tokens = $this->tokens;
            return $result;
        }
        
        function getCurrent(){
            return $this->current;
        }
        
        function setCurrent($val){
            $this->current = $val;
        }
        
        function getCurrentToken (){
            return $this->tokens[$this->current];
        }
        
        function read(){
            $token = $this->tokens[$this->current];
            $this->current += 1;
            return $token;
        }
        
        function getToken($i){
            return $this->tokens[$i];
        }
        
        function getAllTokens(){
            return $this->tokens;
        }
    }
?>
