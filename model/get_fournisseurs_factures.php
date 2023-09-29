<?php
include "../model/connexion.php";

$query = "SELECT f.id, f.nom, COUNT(fact.id) AS nombre_factures 
FROM leparc.fournisseur AS f 
LEFT JOIN leparc.facture AS fact ON f.id = fact.id_fournisseur 
GROUP BY f.id, f.nom;
";

$stmt = $connexion->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['fournisseursFactures' => $result]);
?>