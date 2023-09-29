<?php
include './connexion.php';

if (
    !empty($_POST['prix_unitaire']) &&
    !empty($_POST['quantite']) &&
    !empty($_POST['id_produit']) &&
    !empty($_POST['total']) &&
    !empty($_POST['id_fournisseur']) &&
    !empty($_POST['date_facture']) &&
    isset($_POST['payment_status'])
)  {
    $sql = "UPDATE leparc.facture SET prix_unitaire=?, quantite=?, id_produit=?, total=?,id_fournisseur=?,date_facture=?,id_statue=? WHERE id=?;";
    $req = $connexion->prepare($sql);
    $req->execute(array(
        $_POST['prix_unitaire'],
        $_POST['quantite'],
        $_POST['id_produit'],
        $_POST['total'],
        $_POST['id_fournisseur'],
        $_POST['date_facture'],
        $_POST['payment_status'],
        $_POST["id"]
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['facture_id'])) {
    $facture_id = $_POST['facture_id'];
    
    // Update the facture status to "Payé" and set remaining amount to 0 in the database
    $sql = "UPDATE leparc.facture SET id_statue = 1, montant_restant = 0 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $facture_id);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    
    $stmt->close();
    $conn->close();
}
header('Location:../vue/facture.php');
?>