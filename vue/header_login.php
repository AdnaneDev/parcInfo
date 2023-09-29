<?php
session_start();  
include_once '../model/function.php' ;
?>
<!DOCTYPE html>

<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8" />
    <title> <?php
    echo ucfirst(str_replace(".php","",basename($_SERVER['PHP_SELF']))) ;
    ?></title>


    <link rel="stylesheet" href="../public/css/style.css" />
    <link rel="stylesheet" href="../public/css/login.css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>