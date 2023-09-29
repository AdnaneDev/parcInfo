<?php
include './connexion.php';

if (
    !empty($_POST['id']) &&
    !empty($_POST['nom']) &&
    !empty($_POST["prenom"]) &&
    !empty($_POST["rc"]) &&
    !empty($_POST["nif"]) &&
    !empty($_POST["nis"]) &&
    !empty($_POST["rib"]) &&
    !empty($_POST["email"]) &&
    !empty($_POST["numero"])
) {
    $sql = "UPDATE leparc.fournisseur SET nom=?, prenom=?, rc=?, nif=?, nis=?, rib=?, email=?, numero=? WHERE id=?";
    
    $req = $connexion->prepare($sql);
    $req->execute([
        $_POST['nom'],
        $_POST["prenom"],
        $_POST["rc"],
        $_POST["nif"],
        $_POST["nis"],
        $_POST["rib"],
        $_POST["email"],
        $_POST["numero"],
        $_POST['id']
    ]);

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

header('Location:../vue/fournisseur.php');
?>