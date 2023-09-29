<?php
include './connexion.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $productId = $_GET['id'];
    
    // Utilisez une requête SQL pour récupérer les données du produit en fonction de l'ID
    $sql = "SELECT * FROM leparc.produit WHERE id = ?";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([$productId]);
    $productData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($productData) {
        // Renvoyez les données du produit au format JSON
        echo json_encode($productData);
    } else {
        // Si le produit n'a pas été trouvé, renvoyez un objet JSON vide ou un message d'erreur
        echo json_encode(array('error' => 'Produit non trouvé'));
    }
} else {
    // Si l'ID du produit n'est pas défini ou vide, renvoyez un message d'erreur
    echo json_encode(array('error' => 'ID du produit non spécifié'));
}
?>