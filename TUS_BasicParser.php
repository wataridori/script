<?php
	include (__DIR__.'/TUS_FileReader.php');
    include (__DIR__.'/TUS_Line.php');
    include (__DIR__.'/TUS_Token.php');

    
    
    print_r ($fileReader->getArrayObject());

	class TUS_BasicParser {
		private $source = array();

		function __construct($file_name) {
			$file_path = __DIR__.$file_name;
			$file_reader = new TUS_FileReader($filepath);
			$this->source = $fileReader->getArrayObject();
		}

		function parse() {
			foreach($this->source as $row) {
				foreach($row as $token) {
					switch ($token) {
						case 'value':
							# code...
							break;
						
						default:
							# code...
							break;
					}
				}
			}

			private $is_identifier, $is_string, $is_number, $is_comment;
		}
	}