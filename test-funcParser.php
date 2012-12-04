<?php
    include (__DIR__."/TUS/main.php");    
    $filepath = __DIR__."/funcParser.tus";    
    $fileReader = new TUS_FileReader($filepath);                        
    $p = new TUS_FuncParser($fileReader);        
    $env = new TUS_BasicEnv();
    $p->evaluate($env);
    echo $env->get("result")."\n";
?>