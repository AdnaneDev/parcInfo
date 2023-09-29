<?php
include './connexion.php';

if (
    !empty($_POST['username']) &&
    !empty($_POST["password"]) &&
    !empty($_POST["confirm_password"]) &&
    !empty($_POST['name'])&&
    !empty($_POST['lastname'])&&
    !empty($_POST['id_salle'])&&
   
    !empty($_POST['id_role'])
) {
    $sql = "UPDATE leparc.user SET username=?, password=?, confirm_password=?, name=?,lastname=?,id_salle=?,id_role=? WHERE id=?;";
    $req = $connexion->prepare($sql);
    $req->execute(array(
        $_POST['username'],
        $_POST["password"],
        $_POST["confirm_password"],
        $_POST['name'],
        $_POST['lastname'],
        $_POST['id_salle'],
        $_POST['id_role']
    ));

    if ($req->rowCount() != 0) {
        $_SESSION['message']['text'] = 'user modifié avec succès';
        $_SESSION['message']['type'] = 'success';
    } else {
        $_SESSION['message']['text'] = "Rien n'a été modifié";
        $_SESSION['message']['type'] = 'warning';
    }
} else {
    $_SESSION['Message']['text'] = "Une information manquante";
    $_SESSION['Message']['type'] = 'danger';
}

header('Location:../vue/user.php');
?>