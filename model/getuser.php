<?php
include './connexion.php';

if (!empty($_POST['username']) &&
    !empty($_POST['password'])
) {
    $req = $connexion->prepare("SELECT * FROM leparc.user WHERE username = :username AND password = :password");
    $req->bindParam(":username", $_POST['username']);
    $req->bindParam(":password", $_POST['password']);

    $req->execute(); 
    $reponse = $req->fetch(PDO::FETCH_ASSOC);
    
    if (!$reponse) {
        $messageErreur = "Erreur password ou username";
    header('Location: ../vue/login.php?error=' . urlencode($messageErreur));
    }else{
        session_start();  
        $_SESSION["username"] = $reponse["username"];
        $_SESSION["role"] = $reponse["id_role"];
        $_SESSION["id_user"] = $reponse["id"];
         
        header('Location: ../vue/dashboard.php');
    }
}
?>