<?php 
include './connexion.php';


if (!empty($_POST['id'])) {
    $product_id = $_POST['id'];
   
    $sql = "DELETE FROM leparc.produit WHERE id=?";
    $req = $connexion->prepare($sql);
    $req->execute(array($product_id));
}

header('Location: ../vue/produit.php');
exit();
?>