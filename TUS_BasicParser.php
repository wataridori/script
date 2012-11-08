<?php
	include (__DIR__.'/TUS_FileReader.php');
    include (__DIR__.'/TUS_Line.php');
    include (__DIR__.'/TUS_Token.php');

	class TUS_BasicParser {
		private $source = array();
		private $reserved = array();

		function __construct($file_name) {
			$this->reserved[] = ';';
			$this->reserved[] = '}';

			$file_path = __DIR__.$file_name;
			$file_reader = new TUS_FileReader($filepath);
			$this->source = $fileReader->getArrayObject();
		}

		function parse() {
			foreach($this->source as $row) {
				foreach($row as $token) {
					if($token->isIdentifier()) {

					}

					if($token->isString()) {

					}

					if($token->isNumber()) {

					}

					if($token->isComment()) {

					}
				}
			}

			private $is_identifier, $is_string, $is_number, $is_comment;
		}
	}