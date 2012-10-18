<?php
	include (__DIR__.'/TUS_ASTree.php');
	include (__DIR__.'/TUS_Token.php');

	class TUS_ASTLeaf implements TUS_ASTree {
		protected $token;

		function __construct($token) {
			if($token instanceof TUS_Token) {
				$this->token = $token;
			}
		}

		function child($i) {
			return null;
		}

		function numChildren() {
			return 0;
		}

		function children() {
			return null;
		}

		function location() {
			return "at line " + $token->getLineNumber();
		}

		function toString() {
			return $this->token->getText();
		}
	}