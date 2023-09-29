<?php
include "connexion.php";

$query = "SELECT MONTH(date_demande) AS mois, COUNT(*) AS nombre_demandes FROM leparc.demande GROUP BY mois";
$stmt = $connexion->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$demandesParMois = array_fill(0, 11, 0); // Utiliser les mois de 1 à 12

foreach ($result as $row) {
    $mois = $row['mois'];
    $nombre_demandes = $row['nombre_demandes'];
    $demandesParMois[$mois] = $nombre_demandes;
}

echo json_encode(['demandesParMois' => $demandesParMois]);
?>