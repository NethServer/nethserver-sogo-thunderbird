<?php
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
switch ($lang){
    case "it":
        include("index_it.php");
        break;
    default:
    case "en":
        include("index_en.php");
        break;        
}
?>
