<?php
include './connexion.php';

if (!empty($_GET['id'])) {
    $userID = $_GET['id'];
    
    // Remplacez cette requête par la vôtre pour récupérer les données de l'utilisateur
    $sql = "SELECT u.*, s.id_service
    FROM leparc.user u
    INNER JOIN leparc.salle s ON u.id_salle = s.id
    WHERE u.id =?";
    $req = $connexion->prepare($sql);
    $req->execute(array($userID));
    $userData = $req->fetch(PDO::FETCH_ASSOC);
    
    // Assurez-vous de renvoyer les données sous forme de réponse JSON
    header('Content-Type: application/json');
    echo json_encode($userData);
    exit;
}