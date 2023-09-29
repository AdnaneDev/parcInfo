<?php
include "./header.php";

if(!empty($_GET['id'])){
    $item=getfacture($_GET['id']);  
}
$facturetList = getFacture();


function deleteFacture($facture_id) {
    $sql = "DELETE FROM leparc.facture WHERE id=?";
    $req = $GLOBALS['connexion']->prepare($sql);
    $req->execute(array($facture_id));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_facture_id']) && !empty($_POST['delete_facture_id'])) {
        $facture_id = $_POST['delete_facture_id'];
        deleteFacture($facture_id);
    }
}
?>
<style>

</style>



<div class="home-content">
    <div class="filtration">
        <label for="filterSupplier">Filtrer par fournisseur:</label>
        <select id="filterSupplier" onchange="filterFactures2()">
            <option value="all">Tous les fournisseurs</option>
            <?php
        $fournisseurs = getFournisseur(); // Remplacez cette ligne par la fonction pour obtenir la liste des fournisseurs
        foreach ($fournisseurs as $fournisseur) {
            echo "<option value='{$fournisseur['id']}'>{$fournisseur['nom']}</option>";
        }
    ?>
        </select>


        <label for="filterMonth">Filtrer par mois:</label>
        <select id="filterMonth" onchange="filterFactures3()">
            <option value="all">Tous les mois</option>
            <!-- Generate dynamic month options using JavaScript -->
            <script>
            function generateMonthOptions() {
                const filterMonthSelect = document.getElementById('filterMonth');
                const monthNames = [
                    'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                    'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
                ];

                for (let month = 1; month <= 12; month++) {
                    const option = document.createElement('option');
                    option.value = month;
                    option.textContent = monthNames[month - 1];
                    filterMonthSelect.appendChild(option);
                }
            }

            generateMonthOptions();
            </script>
        </select>



        <label for="filterStatus">Filtrer par statut:</label>
        <select id="filterStatus" onchange="filterFactures()">
            <option value="all">Tous</option>
            <option value="1">Payé</option>
            <option value="3">Partiellement Payé</option>
            <option value="2">Non Payé</option>
        </select>
    </div>

    <div class="box-facture">
        <table id="myDataTable">
            <thead>
                <tr>
                    <th>NumFac</th>
                    <th>Date</th>
                    <th>Fournisseur</th>

                    <th>MontantTVA</th>
                    <th>MontantHT</th>
                    <th>MontantTTC</th>
                    <th>Status</th>
                    <th>Montant Restant</th>
                    <th>Details</th>
                    <th>payement</th>


                </tr>
            </thead>
            <tbody>
                <?php
                $factures=getFacture();
       
  
                if(!empty($factures)&&is_array($factures)){
                    
                    foreach($factures as $key =>$value){
                ?>
                <tr class="facture-row" data-status="<?= $value['id_statue'] ?>"
                    data-supplier="<?= $value['id_fournisseur'] ?>"
                    data-month="<?= date('n', strtotime($value['date_facture'])) ?>">
                    <td class=" facture-cell"><?=$value['id']?></td>
                    <td class="facture-cell"><?=$value['date_facture']?></td>
                    <td class="facture-cell"><?=$value['nom_fournisseur']?></td>

                    <td class="facture-cell"><?=$value['montantTVA']?> DA</td>
                    <td class="facture-cell"><?=$value['montantHT']?> DA</td>
                    <td class="facture-cell"><?=$value['montantTTC']?> DA</td>
                    <td id="statusCell<?= $value['id'] ?>" class="facture-cell  statut-cell"> <?php
        if ($value['id_statue'] == 1) {
            echo 'Payé <span class="circle circle-green"></span>';
        } elseif ($value['id_statue'] == 3) {
            echo 'Partiellement Payé <span class="circle circle-yellow"></span>';
        } elseif ($value['id_statue'] == 2) {
            echo 'Non Payé <span class="circle circle-red"></span>';
        }
        ?>
                    </td>
                    </td>

                    <td id=" montantRestantCell<?= $value['id'] ?>" class="facture-cell">
                        <?= $value['montant_restant'] ?>
                    </td>


                    <td class="facture-cell">
                        <button class="confirm-button" onclick=" openDetailsPopup(<?= $value['id'] ?>)">
                            <i style="color: white; font-size: 24px;" class='bx bx-info-circle'></i>

                        </button>
                    </td>





                    <td>
                        <button class="confirm-button <?= $value['id_statue'] === 1 ? ' disabled' : '' ?>" id="
                     confirmPaymentButton" data-id="<?= $value['id'] ?>"
                            onclick="openPaymentPopup(<?= $value['id'] ?>, '<?= $value['id_statue'] ?>')">Paiement</button>

                    </td>



                </tr>


                <?php
          
                 }
                }
            
                ?>

            </tbody>
            <div id="detailsPopup" class="popup" style="display: none;">
                <div class="popup-content">
                    <h2>Détails de la Facture</h2>
                    <!-- Affichez ici les détails de la facture, vous pouvez utiliser du JavaScript pour les remplir -->
                    <button id="printButton">Imprimer</button>
                    <button id="closeDetailsButton">Fermer</button>
                </div>
            </div>

        </table>

        <!-- Ajoutez cette balise div à la fin de votre page -->
        <div id="paymentPopup" class="popup" style="display: none;">
            <div class="popup-content">
                <h2>Options de Paiement</h2>
                <label for="paymentOptionFull" class="radio-label"> Payer en Totalité
                    <input type="radio" id="paymentOptionFull" name="payment_option" value="full">
                </label>

                <label for="paymentOptionPartial" class="radio-label"> Partiellement Payer
                    <input type="radio" id="paymentOptionPartial" name="payment_option" value="partial">
                </label>
                <div class="button-container">
                    <button id="confirmPaymentButtonPopup">Confirmer</button>
                    <button id="cancelPaymentButtonPopup">Annuler</button>
                </div>

            </div>
        </div>
        <div id="partialPaymentPopup" class="popup" style="display: none;">
            <div class="popup-content">
                <h2>Options de Paiement Partiel</h2>
                <label for="partialAmount">Montant Payé:</label>

                <input type="number" id="partialAmountInput" name="payment_amount" step="1000">
                <div class="button-container"> <button id="confirmPartialPaymentButton">Confirmer</button>
                    <button id="cancelPartialPaymentButton">Annuler</button>
                </div>

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
function openDetailsPopup(id) {
    const detailsPopup = document.getElementById('detailsPopup');
    detailsPopup.style.display = 'block';

    // Récupérez d'abord les détails de base de la facture
    fetch('../model/get_facture_products.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            facture = data[0];
            const dateFacture = facture.date_facture || 'N/A';
            const nomFournisseur = facture.id_fournisseur || 'N/A';
            const montantTVA = facture.montantTVA || 'N/A';
            const montantHT = facture.montantHT || 'N/A';
            const montantTTC = facture.montantTTC || 'N/A';
            const idStatue = facture.id_statue || 'N/A';
            console.log(data)
            const detailsContent = document.createElement('div');
            detailsContent.classList = 'detailsContent';
            detailsContent.innerHTML = `
                <h2>Détails de la Facture #${id}</h2>
                <p>Date : ${dateFacture}</p>
                <p>Fournisseur : ${nomFournisseur}</p>
                <p>Montant TVA : ${montantTVA} DA</p>
                <p>Montant HT : ${montantHT} DA</p>
                <p>Montant TTC : ${montantTTC} DA</p>
                <p>Status : ${getStatusLabel(idStatue)}</p>
            `;

            // Créez un conteneur pour afficher la liste des produits dans les détails de la facture
            const productsContainer = document.createElement('div');
            productsContainer.innerHTML = '<h3>Produits dans la Facture :</h3>';

            // Ajoutez les détails des produits récupérés dans le conteneur
            fetch('../model/get_facture_products.php?id=' + id)
                .then(response => response.json())
                .then(products => {
                    if (products.length > 0) {
                        const productsTable = document.createElement('table');
                        productsTable.classList = "productTable";
                        productsTable.innerHTML = `
                            <thead>
                                <tr>
                                    <th>Désignation</th>
                                    <th>Prix Unitaire</th>
                                    <th>Quantité</th>
                                    <th>Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        `;

                        const productsTableBody = productsTable.querySelector('tbody');
                        products.forEach(product => {
                            const productRow = document.createElement('tr');
                            productRow.innerHTML = `
                                <td>${product.designation}</td>
                                <td>${product.prix_unitaire} DA</td>
                                <td>${product.quantite}</td>
                                <td>${product.prix_total} DA</td>
                            `;
                            productsTableBody.appendChild(productRow);
                        });

                        productsContainer.appendChild(productsTable);
                    } else {
                        productsContainer.innerHTML = 'Aucun produit dans la facture.';
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des produits de la facture :', error);
                });

            // Bouton d'impression
            const printButton = document.createElement('button');
            printButton.textContent = 'Imprimer';
            printButton.classList = 'printButton';
            printButton.onclick = function() {

                window.print()

            };

            const closeDetailsButton = document.createElement('button');
            closeDetailsButton.textContent =
                'Fermer';
            closeDetailsButton.classList = 'closeDetailsButton';
            closeDetailsButton.onclick = function() {
                detailsPopup.style.display = 'none';
            };

            // Ajoutez tous les éléments au contenu des détails
            detailsContent.appendChild(productsContainer);
            detailsContent.appendChild(printButton);
            detailsContent
                .appendChild(closeDetailsButton);

            // Supprimez le contenu actuel du popup s'il existe
            while (detailsPopup.firstChild) {
                detailsPopup.removeChild(detailsPopup.firstChild);
            }

            // Ajoutez le contenu des détails au popup
            detailsPopup.appendChild(detailsContent);
        })
        .catch(error => {
            console.error('Erreur lors du chargement des détails de la facture :', error);
        });
}

function getStatusLabel(statusId) {
    // Vous pouvez mettre en place une fonction pour obtenir le libellé du statut
    // en fonction de son ID ou de tout autre moyen approprié
    switch (statusId) {
        case 1:
            return 'Payé';
        case 2:
            return 'Non Payé';
        case 3:
            return 'Partiellement Payé';
        default:
            return 'Statut Inconnu';
    }
}
</script>

<script>
function confirmFullPayment(id) {
    const confirmed = confirm("Êtes-vous sûr de confirmer le paiement en totalité ?");

    if (confirmed) {
        fetch('../model/updatepayement.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `facture_id=${id}&payment_option=full`,
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    location.reload();
                } else {
                    alert("Échec de la mise à jour du statut de paiement.");
                }
            })
            .catch(error => {
                console.error(error);
                alert("Une erreur s'est produite lors de la mise à jour du statut de paiement.");
            });
    }
}

function confirmPartialPayment(id, amount) {
    const confirmed = confirm("Êtes-vous sûr de confirmer le paiement partiel ?");

    if (confirmed) {
        fetch('../model/updatepayement.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `facture_id=${id}&payment_option=partial&payment_amount=${amount}`,
            })

            .then(response => response.json())
            .then(data => {

                if (data.success) {
                    location.reload();
                } else {
                    alert("Échec de la mise à jour du statut de paiement partiel.");
                }
            })

            .catch(error => {
                console.error(error);
                alert("Une erreur s'est produite lors de la mise à jour du statut de paiement partiel.");
            });
    }
}
</script>


<script src="../public/js/facturejs.js"></script>
<?php 
include "./footer.php";

?>