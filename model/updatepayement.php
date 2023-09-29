<?php
include "./connexion.php"; // Include your database connection

header('Content-Type: application/json'); // Set the content type to JSON

$nouveau_montant_restant = 0; // Définir une valeur par défaut 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['facture_id']) && isset($_POST['payment_option'])) {
    $facture_id = $_POST['facture_id'];
    $payment_option = $_POST['payment_option'];

    try {
       $connexion->beginTransaction();
  // Mettre à jour le statut de paiement en fonction de l'option choisie
        if ($payment_option === 'full') {
            // Paiement en totalité
            $sql = "UPDATE leparc.facture SET id_statue = 1, montant_restant = 0 WHERE id = ?";
            $stmt = $connexion->prepare($sql);
            $stmt->bindValue(1, $facture_id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            echo json_encode(['success' => true, 'nouveau_montant_restant' => $nouveau_montant_restant]);
        }  
        elseif ($payment_option === 'partial') {
            $payment_amount = $_POST['payment_amount'];
        
            // Validate partial payment amount
            $sql = "SELECT total, montant_restant FROM leparc.facture WHERE id = ?";
            $stmt = $connexion->prepare($sql);
            $stmt->bindValue(1, $facture_id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        
            $total = $row['total'];
            $montant_restant = $row['montant_restant'];
        
            if ($payment_amount <= 0 || $payment_amount > $montant_restant) {
                echo json_encode(['success' => false, 'message' => 'Montant de paiement partiel invalide']);
                exit();
            }
        
            // Calculate new remaining amount
            $nouveau_montant_restant = max($montant_restant - $payment_amount, 0);
        
            // Update the facture status and remaining amount
            $sql = "UPDATE leparc.facture SET id_statue = 3, montant_restant = ? WHERE id = ?";
            $stmt = $connexion->prepare($sql);
        
            // Bind the parameters
            $stmt->bindValue(1, $nouveau_montant_restant, PDO::PARAM_STR);
            $stmt->bindValue(2, $facture_id, PDO::PARAM_INT);
        
      
            // Exécuter la mise à jour
            if ($stmt->execute()) {
                $stmt->closeCursor();
                echo json_encode(['success' => true, 'nouveau_montant_restant' => $nouveau_montant_restant]);
            } else {
                $stmt->closeCursor();
                $connexion->rollBack(); // Annuler la transaction
                echo json_encode(['success' => false]);
            }
            
        }
        
        // Mettre à jour le statut à "Payé" si le montant restant est à 0
        if ($nouveau_montant_restant === 0) {
            $statue_paye = 1;
            $sql = "UPDATE leparc.facture SET id_statue = ? WHERE id = ?";
            $stmt = $connexion->prepare($sql);
            $stmt->bindValue(1, $statue_paye, PDO::PARAM_INT);
            $stmt->bindValue(2, $facture_id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
        }
        
        // Valider la transaction si tout s'est bien passé
        $connexion->commit();
    } catch (PDOException $e) {
        // En cas d'erreur, annuler la transaction
       
        echo json_encode(['success' => false, 'message' => 'Une erreur s\'est produite lors de la mise à jour du statut de paiement']);
    }
}
?>