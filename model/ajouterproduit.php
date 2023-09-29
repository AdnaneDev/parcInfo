<?php 
include './connexion.php';

if (!empty($_POST['designation']) && !empty($_POST["ref"]) && !empty($_POST["prix_unitaire"]) && !empty($_POST["id_fournisseur"]) && !empty($_POST["id_categorie"])) {
  if (empty($_POST['id'])) {
      // Ajouter un nouveau produit (opération d'insertion)
      $sql = "INSERT INTO leparc.produit (designation, ref, prix_unitaire, id_fournisseur, id_categorie) VALUES (?, ?, ?, ?, ?)";
      $req = $connexion->prepare($sql);
      $req->execute(array(
          $_POST["designation"],
          $_POST["ref"],
          $_POST["prix_unitaire"],
          $_POST["id_fournisseur"],
          $_POST["id_categorie"]
      ));
  } else {
      // Mettre à jour un produit existant (opération de mise à jour)
      $sql = "UPDATE leparc.produit SET designation=?, ref=?, prix_unitaire=?, id_fournisseur=?, id_categorie=? WHERE id = ?";
      $req = $connexion->prepare($sql);
      $req->execute(array(
          $_POST["designation"],
          $_POST["ref"],
          $_POST["prix_unitaire"],
          $_POST["id_fournisseur"],
          $_POST["id_categorie"],
          $_POST["id"]
      ));
  }

  if ($req->rowCount() != 0) {
      $_SESSION['message']['text'] = empty($_POST['id']) ? 'Matériel ajouté avec succès' : 'Matériel modifié avec succès';
      $_SESSION['message']['type'] = 'success';
  } else {
      $_SESSION['message']['text'] = empty($_POST['id']) ? 'Erreur lors de l\'ajout du matériel' : 'Erreur lors de la modification du matériel';
      $_SESSION['message']['type'] = 'warning';
  }
} else {
  $_SESSION['message']['text'] = 'Des informations sont manquantes';
  $_SESSION['message']['type'] = 'danger';
}

header('Location:../vue/produit.php');

?>