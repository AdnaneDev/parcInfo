<?php
require '../vendor/autoload.php';
include './connexion.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];
   
    // Charger le fichier Excel
    $spreadsheet = IOFactory::load($file);
    $worksheet = $spreadsheet->getActiveSheet();

    // Initialiser un tableau pour stocker toutes les données à insérer
    $allData = [];

    // Parcourir les lignes du fichier Excel
    foreach ($worksheet->getRowIterator() as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
    
        $data = [];
        foreach ($cellIterator as $cell) {
            $cellValue = trim($cell->getValue());
            if ($cellValue !== '') {
                $data[] = $cellValue;
            }
        }

        // Ajouter les données de cette ligne au tableau global, sauf si c'est la première ligne
        if ($data[0] !== 'designation') {
            $allData[] = $data;
        }
    }
    

    // Insérer les données dans la base de données
    foreach ($allData as $data) {
        // Assurez-vous que le tableau contient exactement 5 éléments
        if (count($data) === 5) {
            $designation = $data[0];
            $ref = $data[1];
            $id_categorie = $data[2];
            $prix_unitaire = $data[3];
            $id_fournisseur = $data[4];
    
            // Vérifier si le produit existe déjà dans la base de données
            $existingProductQuery = "SELECT id FROM leparc.produit WHERE designation = ? AND id_fournisseur = ?";
            $existingProductStmt = $connexion->prepare($existingProductQuery);
            $existingProductStmt->execute([$designation, $id_fournisseur]);
            $existingProduct = $existingProductStmt->fetch();
    
            if ($existingProduct) {
                // Le produit existe déjà, mettez à jour le prix unitaire
                $updateProductQuery = "UPDATE leparc.produit SET prix_unitaire = ? WHERE id = ?";
                $updateProductStmt = $connexion->prepare($updateProductQuery);
                $updateProductStmt->execute([$prix_unitaire, $existingProduct['id']]);
            } else {
                // Le produit n'existe pas, insérez-le
                $insertProductQuery = "INSERT INTO leparc.produit (designation, ref, id_categorie, prix_unitaire, id_fournisseur) 
                                        VALUES (?, ?, ?, ?, ?)";
                $insertProductStmt = $connexion->prepare($insertProductQuery);
                $insertProductStmt->execute([$designation, $ref, $id_categorie, $prix_unitaire, $id_fournisseur]);
            }
        }
    }

    $_SESSION['message']['text'] = 'Liste de produits importée avec succès';
    $_SESSION['message']['type'] = 'success';

    header('Location:../vue/produit.php');
}

?>