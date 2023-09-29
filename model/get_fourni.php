<?php
include './connexion.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $fourniId = $_GET['id'];
    

    $sql = "SELECT * FROM leparc.fournisseur WHERE id = ?";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([$fourniId]);
    $productData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($productData) {
       
        echo json_encode($productData);
    } else {
       
        echo json_encode(array('error' => 'fournisseur non trouvé'));
    }
} else {
    
    echo json_encode(array('error' => 'ID du fournisseur non spécifié'));
}
?>