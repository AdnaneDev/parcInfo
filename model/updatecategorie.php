<?php
include './connexion.php';

if (
    !empty($_POST['id']) &&
    !empty($_POST["intitule"])
) {
    $sql = "UPDATE leparc.categorie SET id=?, intitule=? WHERE id=?;";
    $req = $connexion->prepare($sql);
    $req->execute(array(
        $_POST["id"],
        $_POST["intitule"],
        $_POST["id"] // which record to update
    ));

    if ($req->rowCount() != 0) {
        $_SESSION['message']['text'] = 'Fournisseur modifié avec succès';
        $_SESSION['message']['type'] = 'success';
    } else {
        $_SESSION['message']['text'] = "Rien n'a été modifié";
        $_SESSION['message']['type'] = 'warning';
    }
} else {
    $_SESSION['Message']['text'] = "Une information manquante";
    $_SESSION['Message']['type'] = 'danger';
}

header('Location:../vue/categorie.php');
?>