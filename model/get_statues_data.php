<?php
include "./connexion.php"; // Include your database connection

// Query to get the counts of different payment statuses
$query = "
    SELECT
        SUM(CASE WHEN id_statue = 1 THEN 1 ELSE 0 END) AS paye,
        SUM(CASE WHEN id_statue = 2 THEN 1 ELSE 0 END) AS non_paye,
        SUM(CASE WHEN id_statue = 3 THEN 1 ELSE 0 END) AS partiellement_paye
    FROM leparc.facture
";

try {
    $stmt = $connexion->query($query);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['statuesData' => $result]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Une erreur s\'est produite lors de la récupération des données des statues de paiement']);
}
?>