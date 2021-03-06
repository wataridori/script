<?php	
	array_shift($argv);        
	if (count($argv) != 1) {
		echo "Invalid command! Please ensure that you have inputed right command. Example : tus \"file name\" \n";
		return ;
	}
        $filename = $argv[0];
		    
	$filepath = __DIR__."/".$filename;
	if (!file_exists($filepath))
		$filepath = $filename;
	if (!file_exists($filepath)) {
		echo "Could not open input file : {$filename}\n";
		return;
	}
	$ext = pathinfo($filepath, PATHINFO_EXTENSION);
	if ($ext != "tus") {
		echo "Invalid input file ! TUS only accepts files with extension \"tus\" while {$filename}'s extension is {$ext}\n";
		return ;
	}
	
	include ("TUSLib/main.php");	
	
	$fileReader = new TUS_FileReader($filepath);                        
	$p = new TUS_FuncParser($fileReader);            
	$env = new TUS_BasicEnv();    
	$p->evaluate($env);
?>
