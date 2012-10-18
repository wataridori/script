<?php
    include (__DIR__.'/TUS_FileReader.php');
    include (__DIR__.'/TUS_Line.php');
    include (__DIR__.'/TUS_Token.php');
    $filepath = __DIR__."/script.tus";
    $fileReader = new TUS_FileReader($filepath);
    print_r ($fileReader->getTokens());
?>