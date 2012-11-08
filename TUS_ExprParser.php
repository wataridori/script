<?php
    include (__DIR__.'/TUS_Line.php');
    include (__DIR__.'/TUS_Token.php');
    include (__DIR__.'/TUS_ASTree.php');
    include (__DIR__.'/TUS_ASTLeaf.php');
    include (__DIR__.'/TUS_ASTList.php');
    include (__DIR__.'/TUS_BinaryExpr.php');
    include (__DIR__.'/TUS_Name.php');
    include (__DIR__.'/TUS_NumberLiteral.php');

	class TUS_ExprParser {
		private $source = array();

		function __construct($file_name) {
			$file_path = __DIR__.$file_name;
			$file_reader = new TUS_FileReader($filepath);
			$this->source = $fileReader->getArrayObject();
		}

		function main() {
			foreach($this->source as $row) {
				foreach($row as $token) {
					switch ($token->getText()) {
							case '=':
								# code...
								break;
							case '==':
								# code...
								break;
							case '>':
								# code...
								break;
							case '<':
								# code...
								break;
							case '+':
								# code...
								break;
							case '-':
								# code...
								break;
							case '*':
								# code...
								break;
							case '/':
								# code...
								break;
							case '%':
								# code...
								break;
							
							default:
								# code...
								break;
					}
					if()
		}

		function parse() {
			


		}

		function expression() {

		}

		function isToken($name) {
			$token = 
		}

			private $is_identifier, $is_string, $is_number, $is_comment;
		}
	}