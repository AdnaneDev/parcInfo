<?php
include './connexion.php';

if (!empty($_POST['username']) &&
    !empty($_POST["password"]) &&
    !empty($_POST["name"]) &&
    !empty($_POST["lastname"]) &&
    !empty($_POST["id_salle"]) &&
    !empty($_POST['id_role']))
{
    if($_POST["password"]===$_POST["confirm_password"]){
        $sql = "INSERT INTO leparc.user (username,password, name, last_name,id_salle,id_role) VALUES (?,?, ?, ?, ?,?)";
        $req = $connexion->prepare($sql);
        $req->execute(array( 
            $_POST['username'],
            $_POST["password"],
            $_POST["name"],
            $_POST["lastname"],
            $_POST["id_salle"],
            $_POST['id_role']
        ));
    }
   

  $hash=md5($_POST['password']);
}

header('Location:../vue/createuser.php');
?>