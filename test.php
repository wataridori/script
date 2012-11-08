<?php
    include (__DIR__.'/TUS_FileReader.php');
    include (__DIR__.'/TUS_Line.php');
    include (__DIR__.'/TUS_Token.php');
    include (__DIR__.'/TUS_ASTree.php');
    include (__DIR__.'/TUS_ExprParser.php');
    include (__DIR__.'/TUS_OpPrecedenceParser.php');
    
    $filepath = __DIR__."/script.tus";
    $fileReader = new TUS_FileReader($filepath);
    
    $p = new TUS_ExprParser($fileReader);
    $tree = $p->expression();
    echo $tree->toString()."\n";        
?>