<?php
	include (__DIR__.'/TUS_ASTree.php');
	include (__DIR__.'/TUS_Token.php');

	class TUS_ASTList implements TUS_ASTree {
		protected $children;

		function __construct($tokens) {
			foreach($tokens as $token) {
				if($token instanceof TUS_Token) {
					$this->children[] = $token;
				}	
			}
		}

		function child($i) {
			return $this->children[$i];
		}

		function numChildren() {
			return count($this->children);
		}

		function children() {
			return $this->children;
		}

		function location() {
			foreach($child as $children) {
				$string = $child->getLineNumber();
				if($string) {
					return $string;
				}
			}

			return null;
		}

		function toString() {
			$string = '(';

			foreach($children as $child) {
				$string .= ' ';
				$string .= $child->getText();
			}
			$string .= ' )';

			return $string;
		}
	}