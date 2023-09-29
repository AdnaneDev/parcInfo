<?php
// get_produits_by_fournisseur.php

// Inclure votre fichier de connexion à la base de données
include './connexion.php';

if (isset($_GET['fournisseur_id'])) {
    $fournisseurId = $_GET['fournisseur_id'];

    // Requête pour obtenir les produits associés au fournisseur
    $sql = "SELECT id, designation, prix_unitaire FROM leparc.produit WHERE id_fournisseur = ?";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([$fournisseurId]);

    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Renvoyer les résultats au format JSON
    header('Content-Type: application/json');
    echo json_encode($produits);
} else {
    // Renvoyer une réponse vide si l'ID du fournisseur n'est pas fourni
    echo json_encode([]);
}
?>