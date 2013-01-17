<?php    
    foreach (glob(__DIR__."/*.php") as $filename){                
        if ($filename != __DIR__."/main.php"){            
            include $filename;
        }
    }
?>