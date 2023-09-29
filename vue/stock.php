<?php
include './header.php';
include '../model/connexion.php';
$sql = "CREATE TRIGGER update_stock AFTER INSERT ON details_facture
FOR EACH ROW
BEGIN
  -- Vérifier si le produit existe déjà dans le stock
  DECLARE product_count INT;
  SET product_count = (SELECT COUNT(*) FROM stock WHERE id_produit = NEW.id_produit);

  IF product_count > 0 THEN
    -- Le produit existe déjà dans le stock, mettez à jour la quantité et le prix_total
    UPDATE stock
    SET quantite = quantite + NEW.quantite,
        prix_total = prix_total + NEW.prix_total
    WHERE id_produit = NEW.id_produit;
  ELSE
    -- Le produit n'existe pas dans le stock, insérez-le
    INSERT INTO stock (id_produit, quantite, prix_total)
    VALUES (NEW.id_produit, NEW.quantite, NEW.prix_total);
  END IF;
END;
";

// Exécutez la requête pour créer le déclencheur
try {
    $connexion->exec($sql);
    echo "Le déclencheur a été créé avec succès.";
  

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
$stockData = [];
$selectSql = "SELECT s.*,p.designation FROM leparc.stock as s INNER JOIN leparc.produit p ON s.id_produit = p.id;";
try {
    $stmt = $connexion->query($selectSql);
    $stockData = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>


<div class="home-content">
    <h1>Stock des produits</h1>
    <div class="box-stock">
        <table id="myDataTable">
            <thead>
                <tr>
                    <th>Designation</th>
                    <th>Quantite</th>
                    <th>Prix total</th>
                    <th>Plus d'infos</th>
                </tr>
            </thead>
            <tbody>
                <?php
    

                foreach ($stockData as $row) {
                echo "<tr>";
                    echo "<td>{$row['designation']}</td>";
                    echo "<td>{$row['quantite']}</td>";
                    echo "<td>{$row['prix_total']} DA</td>";
                    echo "<td><button class='popup-btn' data-id='{$row['id_produit']}'>Plus d'infos</button></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>



        </table>
    </div>
    <div class="popup-overlay" id="popup">
        <div class="popup-content">
            <button class="close-btn" id="closePopup"><i class='bx bx-x'></i></button>
            <h2>Détails du produit</h2>
            <div style="display: block;" id="popup-details" class="facture-details-table">

            </div>
        </div>
    </div>



</div>
</div>
</section>
<script>
$(document).ready(function() {
    $('#myDataTable').DataTable();
});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const popupBtns = document.querySelectorAll('.popup-btn');
    const popup = document.getElementById('popup');
    const closePopupBtn = document.getElementById('closePopup');
    const popupDetails = document.getElementById('popup-details');

    popupBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const productId = btn.getAttribute('data-id');
            // Envoyer une requête AJAX pour récupérer les détails du produit
            // et mettez à jour le contenu de popupDetails
            // Utilisez productId pour récupérer les détails du produit en fonction de l'ID
            // Exemple d'utilisation de fetch :
            fetch(`../model/get_product_details.php?id=${productId}`)
                .then(response => response.text())
                .then(data => {
                    popupDetails.innerHTML = data;
                    popup.style.display = 'block';
                })
                .catch(error => console.error(error));
        });
    });







    document.addEventListener('DOMContentLoaded', () => {
        const factureDropdown = document.getElementById('factureDropdown');
        const factureIdInput = document.getElementById('factureId');
        const dateFactureInput = document.getElementById('dateFacture');
        const quantiteFactureInput = document.getElementById('quantiteFacture');
        const prixUnitaireFactureInput = document.getElementById('prixUnitaireFacture');

        factureDropdown.addEventListener('change', () => {
            const selectedFactureId = factureDropdown.value;
            if (selectedFactureId !== '') {
                // Envoyer une requête AJAX pour récupérer les informations de la facture sélectionnée
                fetch(`../model/get_product_info.php?id=${selectedFactureId}`)
                    .then(response => response.json())
                    .then(data => {
                        factureIdInput.value = data.id;
                        dateFactureInput.value = data.date_facture;
                        quantiteFactureInput.value = data.quantite;
                        prixUnitaireFactureInput.value = data.prix_unitaire;
                    })
                    .catch(error => console.error(error));
            } else {
                factureIdInput.value = '';
                dateFactureInput.value = '';
                quantiteFactureInput.value = '';
                prixUnitaireFactureInput.value = '';
            }
        });
    });
    closePopupBtn.addEventListener('click', () => {
        popup.style.display = 'none';
    });
});
</script>

<?php
include './footer.php'
?>