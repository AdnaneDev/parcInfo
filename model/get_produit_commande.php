<?php
// Include necessary database connection code or any other required setup
include './connexion.php';

if (isset($_GET['id_commande']) && !empty($_GET['id_commande'])) {
    $idCommande = $_GET['id_commande'];

    $sql = "SELECT pc.quantite, pc.montant_total,p.*, p.designation 
            FROM leparc.produit_commande pc 
            JOIN leparc.produit p ON pc.id_produit = p.id 
            WHERE pc.id_commande = ?;";
    
    $stmt = $connexion->prepare($sql);
    $stmt->execute([$idCommande]);
    
    $productDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return the product details as JSON response
    header('Content-Type: application/json');
    echo json_encode($productDetails);
} else {
    // Handle invalid request
    http_response_code(400);
    echo json_encode(array("message" => "Invalid request."));
}
?>