<?php
include './connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    if (
      
        !empty($_POST['date_commande']) &&
        !empty($_POST['Num_commande']) &&
        !empty($_POST['id_fournisseur']) &&
        !empty($_POST['id_produit']) &&
        !empty($_POST['quantite']) &&
        !empty($_POST['prix_unitaire'])
    ) {
        
        if (!empty($_POST['id_commande'])) {
         
            $id_commande = $_POST['id_commande'];
 
            $sql_update = "UPDATE leparc.commande SET 
                date_commande = ?,
                Num_commande = ?,
                id_fournisseur = ?
                WHERE id = ?";
            $req_update = $connexion->prepare($sql_update);
            $req_update->execute([
                $_POST['date_commande'],
                $_POST['Num_commande'],
                $_POST['id_fournisseur'],
                $id_commande
            ]);

            // Récupérez la liste des produits existants liés à la commande
            $sql_existing_products = "SELECT id_produit FROM leparc.produit_commande WHERE id_commande = ?";
            $stmt_existing_products = $connexion->prepare($sql_existing_products);
            $stmt_existing_products->execute([$id_commande]);

            $existing_product_ids = $stmt_existing_products->fetchAll(PDO::FETCH_COLUMN);
          
            // Comparez les produits existants avec les produits soumis dans le formulaire
            foreach ($_POST['id_produit'] as $key => $id_produit) {
                $quantite = $_POST['quantite'][$key];
                $prix_unitaire = $_POST['prix_unitaire'][$key];
                $montant_total = $quantite * $prix_unitaire;

                if (in_array($id_produit, $existing_product_ids)) {
                    // Mettre à jour le produit existant
                    $sql_update_product = "UPDATE leparc.produit_commande 
                        SET quantite = ?, prix_unitaire = ?, montant_total = ?
                        WHERE id_commande = ? AND id_produit = ?";
                    $stmt_update_product = $connexion->prepare($sql_update_product);
                    $stmt_update_product->execute([$quantite, $prix_unitaire, $montant_total, $id_commande, $id_produit]);
                } else {
                    // Insérer un nouveau produit
                    $sql_insert_product = "INSERT INTO leparc.produit_commande (id_commande, id_produit, quantite, prix_unitaire, montant_total) 
                        VALUES (?, ?, ?, ?, ?)";
                    $stmt_insert_product = $connexion->prepare($sql_insert_product);
                    $stmt_insert_product->execute([$id_commande, $id_produit, $quantite, $prix_unitaire, $montant_total]);
                }
            }

            // Supprimer les produits qui ne sont plus présents dans le formulaire
            $sql_delete_product = "DELETE FROM leparc.produit_commande WHERE id_commande = ? AND id_produit NOT IN (" . implode(',', $_POST['id_produit']) . ")";
            $stmt_delete_product = $connexion->prepare($sql_delete_product);
            $stmt_delete_product->execute([$id_commande]);

            $_SESSION['message']['text'] = 'Commande modifiée avec succès';
            $_SESSION['message']['type'] = 'success';
                
        }
        
        
        
        else {
            // Insertion d'une nouvelle commande
            $sql_insert = "INSERT INTO leparc.commande (`Num_commande`, `date_commande`, `statut_commande`, `id_fournisseur`) VALUES (?, ?, '0', ?)";
            $req_insert = $connexion->prepare($sql_insert);

            $req_insert->execute([
                $_POST['Num_commande'],
                $_POST['date_commande'],
                $_POST['id_fournisseur']
            ]);
         
            // Récupérer l'ID de la commande nouvellement créée
            $id_commande = $connexion->lastInsertId();

            // Insérer les produits de la commande
            $id_produits = $_POST['id_produit'];
            $quantites = $_POST['quantite'];
            $prix_unitaires = $_POST['prix_unitaire'];

            for ($i = 0; $i < count($id_produits); $i++) {
                $id_produit = $id_produits[$i];
                $quantite = $quantites[$i];
                $prix_unitaire = $prix_unitaires[$i];
                $montant_total = $quantite * $prix_unitaire;

                // Insérer les produits de la nouvelle commande
                $sql_insert_product = "INSERT INTO leparc.produit_commande (id_commande, id_produit, quantite, prix_unitaire, montant_total) 
                    VALUES (?, ?, ?, ?, ?)";
                $stmt_insert_product = $connexion->prepare($sql_insert_product);
                $stmt_insert_product->execute([$id_commande, $id_produit, $quantite, $prix_unitaire, $montant_total]);
            }

            $_SESSION['message']['text'] = 'Commande ajoutée avec succès';
            $_SESSION['message']['type'] = 'success';
            print_r($_POST['prix_unitaire']);
        }
    } else {
        $_SESSION['message']['text'] = 'Veuillez remplir tous les champs obligatoires';
        $_SESSION['message']['type'] = 'warning';
    }
 

    header('Location:../vue/commande.php');
}
?>