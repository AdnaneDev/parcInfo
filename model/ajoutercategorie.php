<?php
include './connexion.php';

if (!empty($_POST['intitule']))
{
    $sql = "INSERT INTO leparc.categorie (intitule) VALUES (?)";
    $req = $connexion->prepare($sql);
    $req->execute(array( 
        $_POST["intitule"]
       
    ));

    if ($req->rowCount() != 0) {
        $_SESSION['message']['text'] = 'Fournisseur ajouté avec succès';
        $_SESSION['message']['type'] = 'success';
    } else {
        $_SESSION['message']['text'] = "Une erreur s'est produite";
        $_SESSION['message']['type'] = 'danger';
    }
}

header('Location:../vue/categorie.php');
?>