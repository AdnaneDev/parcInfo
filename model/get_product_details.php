<?php
include 'connexion.php'; 

if (isset($_GET['id'])) {
    $productId = $_GET['id'];

   
    $sqlProduct = "SELECT designation FROM leparc.produit WHERE id = ?";
    $stmtProduct = $connexion->prepare($sqlProduct);
    $stmtProduct->execute([$productId]);
    $productDetails = $stmtProduct->fetch(PDO::FETCH_ASSOC);

    if ($productDetails) {
      
        $productDetailsHTML = '
          
            <p><strong>Désignation:</strong> ' . $productDetails['designation'] . '</p>
        ';

      
        $sqlFactures = "SELECT f.id, f.date_facture, df.quantite, df.prix_unitaire
                        FROM leparc.facture f
                        INNER JOIN leparc.details_facture df ON f.id = df.id_facture
                        WHERE df.id_produit = ?";
        $stmtFactures = $connexion->prepare($sqlFactures);
        $stmtFactures->execute([$productId]);
        $factures = $stmtFactures->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($factures)) {
            $productDetailsHTML .= '<table class="facture-details-table">';
            $productDetailsHTML .= '<thead>';
            $productDetailsHTML .= '<tr>';
            $productDetailsHTML .= '<th>Facture ID</th>';
            $productDetailsHTML .= '<th>Date</th>';
            $productDetailsHTML .= '<th>Quantité</th>';
            $productDetailsHTML .= '<th>Prix unitaire</th>';
            $productDetailsHTML .= '</tr>';
            $productDetailsHTML .= '</thead>';
            $productDetailsHTML .= '<tbody>';
        
            foreach ($factures as $facture) {
                $productDetailsHTML .= '<tr>';
                $productDetailsHTML .= '<td>' . $facture['id'] . '</td>';
                $productDetailsHTML .= '<td>' . $facture['date_facture'] . '</td>';
                $productDetailsHTML .= '<td>' . $facture['quantite'] . '</td>';
                $productDetailsHTML .= '<td>' . $facture['prix_unitaire'] . '</td>';
                $productDetailsHTML .= '</tr>';
            }
        
            $productDetailsHTML .= '</tbody>';
            $productDetailsHTML .= '</table>';
        } else {
            $productDetailsHTML .= '<p>Aucune facture trouvée pour ce produit.</p>';
        }

        echo $productDetailsHTML;
    } else {
        echo '<p>Produit introuvable.</p>';
    }
} else {
    echo '<p>Aucun ID de produit fourni.</p>';
}
?>