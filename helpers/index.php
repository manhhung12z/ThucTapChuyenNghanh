<?php
$helpers = scandir(__DIR__);
foreach ($helpers as $helper) {
    if(preg_match('/\.php$/', $helper, $matches)) {
        if ($helper != 'index.php') {
            require_once "helpers/$helper";
        }
    }
}


?>