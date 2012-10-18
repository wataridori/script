<?php    
    class TUS_Line{
        private $lineNumber;
        private $tokenCount = 0;
        private $text;
        private $numReg ="/[0-9]+/";
        private $strReg = '/\"(\\"|\\\\|\\n|[^"])*\"/';
        private $idenReg = "/[A-Z_a-z][A-Z_a-z0-9]*|==|<=|>=|&&|\|\||[[:punct:]]/";
        private $tokens;
        
        function __construct($text, $lineNumber){
            $this->text = $text;
            $this->lineNumber = $lineNumber;
            $this->parseToken();            
            $reg = "/\\s*|((\/\/.*)|([0-9]+)|(\"(\\\\\"|\\\\\\\\|\\\\n|[^\"])*\")|[A-Z_a-z][A-Z_a-z0-9]*|==|<=|>=|&&|\\|\\||[[:punct:]])?/";
            preg_match_all($reg,$this->text,$matches);
            $i = 0;
            foreach ($matches[0] as $match){
                
                if (preg_match("/\/\/.*/",$match)){                    
                    $token = new TUS_Token($match,TUS_Token::COM,$i,$this->lineNumber);                    
                    $this->tokens[$i++] = $token->getArrayObject();
                }
                if (!preg_match("/[\s]+/", $match) && $match != ""){                    
                    if (preg_match($this->numReg,$match)){
                        $token = new TUS_Token($match,TUS_Token::NUM,$i,$this->lineNumber);
                        $this->tokens[$i++] = $token->getArrayObject();
                    }                    
                    else if (preg_match($this->strReg,$match)){
                        $token = new TUS_Token($match,TUS_Token::STR,$i,$this->lineNumber);
                        $this->tokens[$i++] = $token->getArrayObject();
                    }
                    else if (preg_match($this->idenReg,$match)){
                        $token = new TUS_Token($match,TUS_Token::IDE,$i,$this->lineNumber);
                        $this->tokens[$i++] = $token->getArrayObject();
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
        
        function parseToken(){
            
        }
        
        function getArrayObject(){
            $result = new ArrayObject;
            $result->text = $this->text;
            $result->lineNumber = $this->lineNumber;
            $result->tokenCount = $this->tokenCount;
            $result->tokens = $this->tokens;
            return $result;
        }
        
    }
?>
