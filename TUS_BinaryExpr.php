<?php
	include (__DIR__.'/TUS_ASTLeaf.php');

	public class TUS_BinaryExpr extends TUS_ASTList {
		function left() {
			return $this->children[0];
		}

		function operator() {
			return $this->children[0]->getText();
		}

		function right() {
			return $this->children[1];
		}
	}