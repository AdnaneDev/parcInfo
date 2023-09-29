<?php
include './connexion.php'; // Incluez votre fichier de connexion à la base de données

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $facture_id = $_GET['id'];

    // Requête SQL pour récupérer les produits associés à la facture depuis la table details_facture
    $sql = "SELECT f.*, p.designation, p.prix_unitaire, df.quantite, df.prix_total
    FROM leparc.details_facture df
    INNER JOIN leparc.produit p ON df.id_produit = p.id
    INNER JOIN leparc.facture f ON df.id_facture = f.id
    WHERE df.id_facture = ?;";
    $req = $connexion->prepare($sql);
    $req->execute(array($facture_id));
    
    $products = $req->fetchAll(PDO::FETCH_ASSOC);
    
    // Renvoie les produits au format JSON
    header('Content-Type: application/json');
    echo json_encode($products);
} else {
    
  
    echo json_encode(array('error' => 'ID de facture non valide'));
}
?>