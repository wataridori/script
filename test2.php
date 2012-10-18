<?php
    include (__DIR__.'/TUS_FileReader.php');
    include (__DIR__.'/TUS_Line.php');
    include (__DIR__.'/TUS_Token.php');
    include (__DIR__.'/TUS_ASTree.php');
    include (__DIR__.'/TUS_ASTNode.php');
    $filepath = __DIR__."/script2.tus";
    $fileReader = new TUS_FileReader($filepath);
    $line = $fileReader->getLine(0);
    $tokens = $fileReader->getTokens();    
    $ast = new TUS_ASTree($tokens);
    $postfixarr = $ast->getPostfixArray();
    foreach ($postfixarr as $i){
        echo $tokens[$i]->text;
    }
    $ast->buildTree();
    //print_r($ast->buildTree());
?>