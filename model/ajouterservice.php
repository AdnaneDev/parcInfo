<?php
include './connexion.php';

if (!empty($_POST['nom']))
{
    $sql = "INSERT INTO leparc.service (nom) VALUES (?)";
    $req = $connexion->prepare($sql);
    $req->execute(array( 
        $_POST["nom"]
       
    ));

    if ($req->rowCount() != 0) {
        $_SESSION['message']['text'] = 'Fournisseur ajouté avec succès';
        $_SESSION['message']['type'] = 'success';
    } else {
        $_SESSION['message']['text'] = "Une erreur s'est produite";
        $_SESSION['message']['type'] = 'danger';
    }
}

header('Location:../vue/services.php');
?>