<?php
include './connexion.php';
if (isset($_GET['id']) && isset($_GET['status'])) {
    $orderId = $_GET['id'];
    $newStatus = $_GET['status'];
   // Récupérez le statut actuel de la commande dans la base de données
   $sqlStatus = "SELECT statut_commande FROM leparc.commande WHERE id = ?";
   $stmtStatus = $connexion->prepare($sqlStatus);
   $stmtStatus->execute([$orderId]);
   $currentStatus = $stmtStatus->fetchColumn();

   // Vérifiez si la commande est déjà confirmée ou annulée
   if ($currentStatus == 1 || $currentStatus == -1) {
       $response = [
           'success' => false,
           'message' => 'La commande ne peut pas être modifiée car elle est déjà confirmée ou annulée.'
       ];
   } else {
    // Mettre à jour le statut de la commande dans la base de données
    $sql = "UPDATE leparc.commande SET statut_commande = ? WHERE id = ?";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([$newStatus, $orderId]);

    if ($stmt->rowCount() != 0) {
        $response = [
            'success' => true,
            'message' => 'Statut de la commande mis à jour avec succès.'
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Échec de la mise à jour du statut de la commande.'
        ];
    }
   }
    // Envoyer la réponse JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    $response = [
        'success' => false,
        'message' => 'Paramètres manquants.'
    ];

    // Envoyer la réponse JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}

?>