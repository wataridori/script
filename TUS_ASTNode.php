<?php
    class TUS_ASTNode {
        protected $leftChild = null;
        protected $rightChild = null;
        protected $token = null;
        
        public function __construct($token = null, $leftChild = null, $rightChild = null){
            if ($token){
                $this->setToken($token);
                if ($leftChild) $this->setLeftChild($leftChild);
                if ($rightChild) $this->setRightChild($rightChild);
            }
        }
        
        public function getToken(){
            return $this->token;
        }
        
        public function setToken($token){
            $this->token = $token;
        }
        
        public function getLeftChild(){
            return $this->leftChild;
        }
        
        public function setLeftChild($child){
            $this->leftChild = $child;
        }
        
        public function getRightChild(){
            return $this->rightChild;
        }
        
        public function setRightChild($child){
            $this->rightChild = $child;
        }
        
        public function isLeaf(){
            return ($this->leftChild == null && $this->rightChild == null);
        }
    }
?>