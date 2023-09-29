<?php
include './connexion.php';

// Exécutez une requête SQL pour obtenir les données des fournisseurs et de leurs dettes
$sql = "SELECT f.nom AS nom_fournisseur, SUM(total-montant_restant) AS depense 
FROM leparc.facture AS fa 
INNER JOIN leparc.fournisseur AS f ON fa.id_fournisseur = f.id 
GROUP BY fa.id_fournisseur;";
$result = $connexion->query($sql);

$fournisseursDepense = array();

if ($result->rowCount() > 0) { // Utilisez rowCount() au lieu de num_rows
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // Créez un tableau associatif pour chaque fournisseur et sa dette
        $fournisseursDepense = array(
            "fournisseur" => $row["nom_fournisseur"],
            "Depense" => floatval($row["depense"]) // Assurez-vous que la dette est un nombre
        );

        // Ajoutez ce tableau au tableau principal
        $fournisseursDepenses[] = $fournisseursDepense;
    }
}

// Fermez la connexion à la base de données
$connexion = null; // Fermez la connexion en attribuant null à la variable

// Convertissez le tableau des données en format JSON
$jsonData = json_encode($fournisseursDepenses);

// Définissez l'en-tête Content-Type pour indiquer que la réponse est au format JSON
header('Content-Type: application/json');

// Renvoyez les données JSON en réponse
echo $jsonData;
?>