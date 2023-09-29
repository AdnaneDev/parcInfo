<?php
// Inclure le fichier de connexion à la base de données
include './connexion.php';

// Assurez-vous que l'ID du service est passé en tant que paramètre GET
if (isset($_GET['id_service'])) {
    $idService = $_GET['id_service'];

    // Préparez une requête SQL pour récupérer les salles associées à ce service
    $sql = "SELECT id, nom FROM leparc.salle WHERE id_service = :idService";

    // Préparez la requête SQL et exécutez-la
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':idService', $idService, PDO::PARAM_INT);
    $stmt->execute();

    // Récupérez les résultats de la requête
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retournez les résultats au format JSON
    echo json_encode($result);
} else {
    // Si l'ID du service n'est pas passé en paramètre, renvoyez une réponse vide ou un message d'erreur
    echo json_encode([]);
}
?>