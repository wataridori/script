<?php
	include (__DIR__.'/TUS_ASTLeaf.php');

	public class TUS_NumberLiteral extends TUS_ASTLeaf {
		function value() {
			return $this->token->getText();
		}
	}