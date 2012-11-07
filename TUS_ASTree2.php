<?php
    class TUS_ASTree {
        protected $contextStack = array();
        protected $tree = null;
        protected $tokens = array();
        protected $postfix = array();
        const LP = "Left Parentheses";
        const RP = "Right Parentheses";
        const OPERATOR = "Operator";
        const OPERAND = "Operand";
        
        const LPP = 0; //Left Parentheses Precedence
        const AP = 1; //Addition Precedence
        const SP = 1; //Subtraction Precedence
        const MP = 2; //Multiplication Precedence
        const DP = 2; //Divisor Precedence
        
        const REMP = 2; //Remainder Precedence
        const NONE = 9;
        
        public function __construct($tokens = null) {
            if ($tokens) {                    
                    $this->setTokens($tokens);
                    $this->convertToPostfix();
            }
        }
        
        public function setTokens($tokens){
            $this->tokens = $tokens;
        }
        
        public function getTokens(){
            return $this->tokens;
        }
        
        public function pushContext($context){
            array_push($this->contextStack, $context);
        }
        
        public function pop_context() {
            return array_pop($this->contextStack);
        }
        
        public function getTree(){
            return $this->tree;
        }
        
        public function getPostfixArray(){
            return $this->postfix;
        }
        
        private function convertToPostfix(){
            $stack = array();
            $tokens = $this->tokens;
            foreach($tokens as $token){                                
                $type = $this->getTokenType($token);
                switch ($type){
                    case self::LP : array_push($stack,$token->tokenOrder); break;
                    case self::RP :                        
                        while (true){
                            $next = $tokens[array_pop($stack)];
                            if ($next->text == '(') break;
                            $this->postfix[] = $next->tokenOrder;
                        }
                        break;
                    case self::OPERAND :
                        $this->postfix[] = $token->tokenOrder;
                        break;
                    case self::OPERATOR :
                        $prec = $this->getTokenPrecedence($token);                        
                        while (!empty($stack) && $prec <= $this->getTokenPrecedence($tokens[$stack[count($stack)-1]])){                            
                            $this->postfix[] = array_pop($stack);                            
                        }
                        array_push($stack,$token->tokenOrder);    
                        break;
                }
            }
            while (!empty($stack)){
                $this->postfix[] = array_pop($stack);
            }
        }
        
        private function getTokenType($token){
            switch ($token->text){
                case '(' : return self::LP;
                case ')' : return self::RP;
                case '+' :
                case '-' :
                case '*' :
                case '/' :
                case '%' :
                    return self::OPERATOR;
                default : return self::OPERAND;
            }            
        }
        
        private function getTokenPrecedence($token){
            switch ($token->text){
                case '(' : return self::LPP;                
                case '+' : return self::AP;
                case '-' : return self::SP;
                case '*' : return self::MP;                    
                case '/' : return self::DP;
                case '%' : return self::REMP;                    
                default : return self::NONE;
            }
        }
        
        public function buildTree(){
            $postfix = $this->postfix;
            $stack = array();
            foreach ($postfix as $p){
                $token = $this->tokens[$p];                
                if ($this->getTokenType($token) == self::OPERAND){
                    array_push($stack,new TUS_ASTNode($token));                    
                }
                else if ($this->getTokenType($token) == self::OPERATOR){
                    $rightChild = array_pop($stack);
                    $leftChild = array_pop($stack);                    
                    array_push($stack,new TUS_ASTNode($token,$leftChild,$rightChild));
                }
            }            
            return array_pop($stack);
        }
    }
?>