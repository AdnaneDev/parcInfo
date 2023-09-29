<?php
// Inclure votre fichier de configuration de base de données

use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Arabic;

include './connexion.php';

// Requête SQL pour récupérer le nombre de demandes par service
$sql = "SELECT s.nom AS service, COUNT(d.id) AS nombre_demandes
FROM leparc.service s
LEFT JOIN leparc.salle sa ON s.id = sa.id_service
LEFT JOIN leparc.user u ON sa.id = u.id_salle
LEFT JOIN leparc.demande d ON u.id = d.id_user
GROUP BY s.nom";

try {
    $stmt = $connexion->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Créer un tableau associatif pour stocker les données
    $data = array();

    foreach ($results as $row) {
        $data[] = array(
            'service' => $row['service'],
            'nombre_demandes' => intval($row['nombre_demandes'])
        );
    }
    // Convertir le tableau associatif en format JSON
    $jsonResponse = json_encode(array('demandesParService' => $data));

    // Envoyer la réponse JSON au client
    header('Content-Type: application/json');
    echo $jsonResponse;
} catch (PDOException $e) {
    // En cas d'erreur de la base de données, renvoyer une réponse d'erreur JSON
    echo json_encode(array('error' => 'Erreur de base de données : ' . $e->getMessage()));
}
?>