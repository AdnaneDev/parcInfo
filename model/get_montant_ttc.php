<?php
include "./connexion.php"; // Include your database connection

header('Content-Type: application/json'); // Set the content type to JSON

try {
    // Query to get the sum of montant_ttc for each month
    $sql = "SELECT MONTH(date_facture) AS mois, SUM(montantTTC-montant_restant) AS montant_ttc FROM leparc.facture GROUP BY mois";
    $stmt = $connexion->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Create an associative array to store montant_ttc for each month
    $montantsTTC = array_fill(0, 12, 0); // Initialize all months with montant 0

    // Fill in the montantsTTC array with actual values
    foreach ($result as $row) {
        $mois = intval($row['mois']) - 1; // Adjust month index (0 = Jan)
        $montantTTC = floatval($row['montant_ttc']);
        $montantsTTC[$mois] = $montantTTC;
    }

    // Create an array of labels for the months
    $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    // Combine labels and montantTTC data
    $data = [];
    foreach ($montantsTTC as $mois => $montant) {
        $data[] = ['label' => $labels[$mois], 'montantTTC' => $montant];
    }

    echo json_encode(['montantsTTC' => $data]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'An error occurred while retrieving TTC amounts']);
}
?>