<?php
    class TUS_FileReader {
        private $fileName;
        private $filePath;
        private $fileContent;
        private $lines;
        private $lineCount;
        
        function __construct($filePath){
            $this->filePath = $filePath;
            $pathinfo = pathinfo($filePath);            
            $this->fileName = $pathinfo['basename'];
            $this->fileContent = file_get_contents($this->filePath, true);            
            $this->lines = explode("\n",$this->fileContent);            
            $this->lineCount = count($this->lines);            
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