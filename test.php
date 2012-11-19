<?php
    include (__DIR__.'/TUS_FileReader.php');
    include (__DIR__.'/TUS_Line.php');
    include (__DIR__.'/TUS_Token.php');
    include (__DIR__.'/TUS_ASTree.php');
    include (__DIR__.'/TUS_ExprParser.php');
    include (__DIR__.'/TUS_OpPrecedenceParser.php');
    include (__DIR__.'/TUS_BasicParser.php');
    include (__DIR__.'/TUS_BasicEnv.php'); 
    
    $filepath = __DIR__."/script.tus";
    $fileReader = new TUS_FileReader($filepath);        
    $p = new TUS_BasicParser($fileReader);
    print_r($p->parse());
    
    $fileReader->setCurrent(0);
    $p = new TUS_BasicParser($fileReader);    
    $env = new TUS_BasicEnv();
    $p->evaluate($env);
    print_r($env);
?>