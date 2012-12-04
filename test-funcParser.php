<?php
    include (__DIR__."/TUS/main.php");    
    $filepath = __DIR__."/funcParser.tus";    
    $fileReader = new TUS_FileReader($filepath);                        
    $p = new TUS_FuncParser($fileReader);            
    print_r($p->parse());            
?>