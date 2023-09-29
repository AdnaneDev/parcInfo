<?php
// Include necessary database connection code or any other required setup
include './connexion.php';

if (isset($_GET['id_commande']) && !empty($_GET['id_commande'])) {
    $idCommande = $_GET['id_commande'];

    $sql = "SELECT c.id,c.date_commande, c.Num_commande, c.id_fournisseur, p.id as id_produit,p.designation, p.prix_unitaire, pc.quantite
    FROM leparc.commande c
    JOIN leparc.produit_commande pc ON c.id = pc.id_commande
    JOIN leparc.produit p ON pc.id_produit = p.id
    WHERE c.id = ?;";

    $stmt = $connexion->prepare($sql);
    $stmt->execute([$idCommande]);

    $commandeDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Vérifiez si des données de commande sont disponibles
    if (!empty($commandeDetails)) {
        // Organisez les données en un tableau d'objets produits
        $produits = [];
        foreach ($commandeDetails as $row) {
            $produit = [
                'id_produit' => $row['id_produit'],
                'designation' => $row['designation'], 
                'prix_unitaire' => $row['prix_unitaire'],
                'quantite' => $row['quantite']
            ];
            $produits[] = $produit;
        }

        // Construisez le tableau de données final
        $commandeData = [
            'id_commande' =>$commandeDetails[0]['id'],
            'date_commande' => $commandeDetails[0]['date_commande'],
            'Num_commande' => $commandeDetails[0]['Num_commande'],
            'id_fournisseur' => $commandeDetails[0]['id_fournisseur'],
            'produits' => $produits
        ];

        // Return the commande details as JSON response
        header('Content-Type: application/json');
        echo json_encode($commandeData);
    } else {
        // Handle the case where no data is found or $commandeDetails is empty
        http_response_code(404);
        echo json_encode(array("message" => "Commande introuvable."));
    }
} else {
    // Handle invalid request
    http_response_code(400);
    echo json_encode(array("message" => "Invalid request."));
}
?>