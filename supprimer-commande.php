<?php
require_once("includes/bdd.php");
session_start();

    if(!isset($_POST["id-commande"]) || !isset($_POST["date-finalisation"])){
        header("Location: index.php");
    }

    extract($_POST);

?>