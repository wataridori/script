<?php
    include (__DIR__."/TUSLib/main.php");    
    $filepath = __DIR__."/builtinFunc.tus";    
    $fileReader = new TUS_FileReader($filepath);                        
    $p = new TUS_FuncParser($fileReader);            
    $env = new TUS_BasicEnv();    
    $p->evaluate($env);
?>