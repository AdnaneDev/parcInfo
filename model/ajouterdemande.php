<?php 
session_start();

include "./connexion.php";

$connexion = $GLOBALS['connexion'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date_demande = $_POST['date_commande'];
    $id_user = $_SESSION['id_user'];
    $id_statue = 2; 

    // Loop through the arrays of products and quantities
    if (isset($_POST['id_produit']) && isset($_POST['quantite']) && isset($_POST['description'])) {
        $id_produits = $_POST['id_produit'];
        $quantites = $_POST['quantite'];
        $descriptions = $_POST['description'];


        // Insert each demand entry into the database
        foreach ($id_produits as $key => $id_produit) {
            $quantite = $quantites[$key];
            $description = $descriptions[$key];

          
        }
        $sql = "INSERT INTO leparc.demande (date_demande, id_produit, id_user, quantite, description, id_statue) VALUES (?, ?, ?, ?, ?, ?)";
        $req = $connexion->prepare($sql);
        $req->execute([$date_demande, $id_produit, $id_user, $quantite, $description, $id_statue]);
        // Redirect the user or provide a success message
    } else {
        echo'ERREUR';
    }
}

// Close the database connection
$connexion = null;

header('Location:../vue/demande.php');
?>