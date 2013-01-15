<?php
    include (__DIR__."/TUSLib/main.php");
    
    $filepath = __DIR__."/basicParser.tus";
    $fileReader = new TUS_FileReader($filepath);        
    $p = new TUS_BasicParser($fileReader);
    print_r($fileReader);
    
    $fileReader->setCurrent(0);
    $p = new TUS_BasicParser($fileReader);    
    $env = new TUS_BasicEnv();
    $p->evaluate($env);
    print_r($env);
?>