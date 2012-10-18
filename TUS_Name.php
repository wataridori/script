<?php
	include (__DIR__.'/TUS_ASTLeaf.php');

	public class TUS_Name extends TUS_ASTLeaf {
		function name() {
			return $this->token->getText();
		}
	}