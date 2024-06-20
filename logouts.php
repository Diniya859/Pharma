<?php
if(!isset($_SESSION)){
    session_start();
}
 //session is a way to store information (in variables) to be used across multiple pages.  
session_destroy();
header("Location: indexs.php");//use for the redirection to some page  
