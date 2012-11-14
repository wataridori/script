<?php
    include (__DIR__.'/TUS_FileReader.php');
    include (__DIR__.'/TUS_Line.php');
    include (__DIR__.'/TUS_Token.php');
    include (__DIR__.'/TUS_ASTree.php');
    include (__DIR__.'/TUS_ExprParser.php');
    include (__DIR__.'/TUS_OpPrecedenceParser.php');
    include (__DIR__.'/TUS_BasicParser.php');        
    
    $filepath = __DIR__."/script.tus";
    $fileReader = new TUS_FileReader($filepath);    
    //$p1 = new TUS_ExprParser($fileReader);
    //print_r($p1->expression());
    //$fileReader->setCurrent(0);
    
    $p2 = new TUS_BasicParser($fileReader);
    $tree = $p2->parse();
    print_r ($tree);
?>