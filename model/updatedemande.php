<?php
include "./connexion.php";
header('Content-Type: application/json'); // Définissez le type de contenu sur JSON
$connexion = $GLOBALS['connexion'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['demand_id'], $_POST['status'])) {
        $demandId = $_POST['demand_id'];
        $status = $_POST['status'];

        // Commencez une transaction pour effectuer les mises à jour atomiquement
        $connexion->beginTransaction();

        try {
            // Récupérez la quantité demandée
            $sql = "SELECT quantite FROM leparc.demande WHERE id = :demandId";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':demandId', $demandId, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                // La demande n'existe pas
                throw new Exception("La demande n'existe pas.");
            }

            $quantiteDemandee = $row['quantite'];

            // Vérifiez si la quantité demandée existe en stock
            $sql = "SELECT quantite FROM leparc.stock WHERE quantite >= :quantiteDemandee";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':quantiteDemandee', $quantiteDemandee, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
        
                throw new Exception("La quantité demandée n'est pas disponible en stock.");
                
            }else{
                   // Mettez à jour le statut de la demande dans la base de données
            $sql = "UPDATE leparc.demande SET id_statue = :status WHERE id = :demandId";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':status', $status, PDO::PARAM_INT);
            $stmt->bindParam(':demandId', $demandId, PDO::PARAM_INT);
            $stmt->execute();

            // Déstockez la quantité demandée
            $sql1 = "UPDATE leparc.stock SET quantite = quantite - :quantiteDemandee WHERE quantite >= :quantiteDemandee";
            $stmt = $connexion->prepare($sql1);
            $stmt->bindParam(':quantiteDemandee', $quantiteDemandee, PDO::PARAM_INT);
            $stmt->execute();

            // Validez la transaction
            $connexion->commit();

            // Tout s'est bien passé
            echo json_encode(array("success" => true));
            }

         
        } catch (Exception $e) {
            // En cas d'erreur, annulez la transaction
            $connexion->rollback();

            // Erreur lors de la mise à jour de la demande
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
        }
        exit();
    }
}
?>