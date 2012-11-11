<?php
    class TUS_FileReader {
        private $fileName;
        private $filePath;
        private $fileContent;
        private $lines;
        private $lineCount;
        private $tokens = array();
        private $current = 0;
        private $count;
        
        function __construct($filePath){            
            $this->filePath = $filePath;
            $pathinfo = pathinfo($filePath);            
            $this->fileName = $pathinfo['basename'];
            $this->fileContent = file_get_contents($this->filePath, true);            
            $this->lines = explode("\n",$this->fileContent);            
            $this->lineCount = count($this->lines);
            $j=0;
            for ($i=0;$i<$this->lineCount;$i++){
                $line = $this->getLine($i);
                $lineTokens = $line->getAllTokens();
                foreach ($lineTokens as $token){
                    $token->tokenOrder = $j++;
                    $this->tokens[] = $token;
                }                
                $this->tokens[] = new TUS_Token(TUS_Token::EOL,TUS_Token::EOL,$j++,$i);                        
            }
            $this->count = $j;
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
            if (!$this->hasMore()) return null;
            $token = $this->tokens[$this->current];
            $this->current += 1;
            return $token;
        }
        
        function hasMore(){
            return ($this->current < $this->count);
        }
        
        function getToken($i){
            return $this->tokens[$i];
        }
        
        function getAllTokens(){
            return $this->tokens;
        }
        
        function getLine($lineNumber){
            return new TUS_Line($this->lines[$lineNumber],$lineNumber);
        }
        
        function getArrayObject(){
            $result = new ArrayObject;
            $result->fileName = $this->fileName;
            $result->filePath = $this->filePath;
            $result->fileContent = $this->fileContent;
            $result->lineCount = $this->lineCount;
            $result->lines = array();
            for ($i=0;$i<$this->lineCount;$i++){
                $newline = new TUS_Line($this->lines[$i],$i);
                $result->lines[] = $newline->getArrayObject();
            }
            return $result;
        }
    }
?>