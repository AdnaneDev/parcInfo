<?php
include './connexion.php';


    
    if (
        !empty($_POST['total']) &&
        !empty($_POST['id_fournisseur']) &&
        !empty($_POST['date_facture']) &&
        isset($_POST['payment_status'])
    ) {
    
  
      $sql = "INSERT INTO leparc.facture ( `total`, `id_fournisseur`, `date_facture`, `id_statue`) 
      VALUES (?, ?, ?, ?)";
$req = $connexion->prepare($sql);

$req->execute(array(
  $_POST["total"],
  $_POST["id_fournisseur"],
  $_POST["date_facture"],
  $_POST['payment_status']
));

if( $req->rowCount()!=0){
  $_SESSION['message']['text']='Matereil ajouter avec succeses';
  $_SESSION['message']['type']='succes';

}
else{
  $_SESSION['message']['text']="une erreur s'est produit";
  $_SESSION['message']['type']='danger';
}


}
else{
$_SESSION['Message']['text']="une information manquante";
$_SESSION['Message']['type']='danger';
}



if (isset($_GET['orderId'])) {
  $orderId = $_GET['orderId'];
 
    // Récupérez les informations du fournisseur de la commande
    $sqlFournisseur = "SELECT id_fournisseur FROM leparc.commande WHERE id = ?";
    $stmtFournisseur = $connexion->prepare($sqlFournisseur);
    $stmtFournisseur->execute([$orderId]);
    $fournisseurId = $stmtFournisseur->fetchColumn();

    /************************************************** */



    

  // Sélectionnez les informations de la commande et les produits associés
  $sql = "SELECT c.*, pc.id_produit, pc.quantite, pc.prix_unitaire
  FROM leparc.commande c
  INNER JOIN leparc.produit_commande pc ON c.id = pc.id_commande
  WHERE c.id = ?";
  
  $stmt = $connexion->prepare($sql);
  $stmt->execute([$orderId]);

  $commandeData = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Calculer les montants et totaux pour la facture
  $montantTotal = 0;
  $montantHt = 0;
  $montantTVA = 2;

  foreach ($commandeData as $row) {
      $prixTotalProduit = $row['quantite'] * $row['prix_unitaire'];
      $montantTotal += $prixTotalProduit;

      // Récupérer le taux de TVA correspondant à ce produit depuis la table `tva`
      $sqlTVA = "SELECT taux FROM leparc.tva WHERE id_produit = ?";
      $stmtTVA = $connexion->prepare($sqlTVA);
      $stmtTVA->execute([$row['id_produit']]);
      $tauxTVA = $stmtTVA->fetchColumn();

      // Calculer le montant HT et le montant TVA en utilisant le taux de TVA
      $montantHTProduit = $prixTotalProduit / (1 + $tauxTVA);
      $montantTVAProduit = $prixTotalProduit - $montantHTProduit;

      // Ajouter les montants HT et TVA au total général
      $montantHt += $montantHTProduit;
      $montantTVA += $montantTVAProduit;
  }
  $montantrestant=$montantTotal;
  
  $montantTTC = $montantHt + $montantTVA;

  $sqlInsertFacture = "INSERT INTO leparc.facture (id_fournisseur, date_facture, id_statue, total, montantHt, montantTTC, montantTVA,montant_restant)
                       VALUES (?, ?, ?, ?, ?, ?, ?,?)";
  $stmtInsertFacture = $connexion->prepare($sqlInsertFacture);
  $stmtInsertFacture->execute([$fournisseurId, date('Y-m-d'),2, $montantTotal, $montantHt, $montantTTC, $montantTVA,$montantrestant]);
  
  // Récupérer l'ID de la nouvelle facture
  $nouvelIdDeLaFacture = $connexion->lastInsertId();


  
  $sqlCommandeProduit = "SELECT id_produit, quantite,prix_unitaire,montant_total FROM leparc.produit_commande WHERE id_commande = ?";
  $stmtCommandeProduit = $connexion->prepare($sqlCommandeProduit);
  $stmtCommandeProduit->execute([$orderId]);
  $commandeProduits = $stmtCommandeProduit->fetchAll(PDO::FETCH_ASSOC);
  // Insérer les produits de la commande dans la table details_facture
  foreach ($commandeProduits as $produit) {
      $idProduit = $produit['id_produit'];
      $quantite = $produit['quantite'];
      $prix_unitaire = $produit['prix_unitaire'];
      $prix_total=$produit['montant_total'];

      $sqlInsertDetails = "INSERT INTO leparc.details_facture (id_facture,id_produit,quantite,prix_unitaire,prix_total)
                          VALUES (?,?, ?,?,?)";
      $stmtInsertDetails = $connexion->prepare($sqlInsertDetails);
   $stmtInsertDetails->execute([$nouvelIdDeLaFacture,$idProduit, $quantite, $prix_unitaire,$prix_total]);
 
  }

  // Rediriger vers la page d'affichage de la nouvelle facture
  $response = [
    'success' => true,
    'message' => 'Commande confirmée et facture ajoutée avec succès.'
];
 
 
} else {
  
    // Réponse JSON en cas d'échec
    $response = [
        'success' => false,
        'message' => 'Erreur lors de l\'ajout de la facture.'
    ];
    

}

header('Content-Type: application/json');
echo json_encode($response);
?>