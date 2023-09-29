<?php
include "./header.php";

if(!empty($_GET['id'])){
    $item=getCommande($_GET['id']);  
}
$commandeList = getCommande();


function annulerCommande($commande_id) {
    $sql = "UPDATE leparc.commande SET statut_commande = '-1' WHERE id = ?";
    $req = $GLOBALS['connexion']->prepare($sql);
    $req->execute(array($commande_id));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_commande_id']) && !empty($_POST['delete_commande_id'])) {
        $commande_id = $_POST['delete_commande_id'];
        annulerCommande($commande_id);
    }
}
?>
<style>
/* Style de base pour les alertes */
.alert {
    position: fixed;
    padding: 10px;
    top: 80px;
    left: 65%;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: red;
    z-index: 100000;
}


.alert-success {
    background-color: #4CAF50;
    color: white;

    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
}


.alert-error {
    background-color: #f44336;
    /* Couleur de fond rouge */
    color: white;
    /* Couleur du texte blanche */
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
}
</style>

<div class="home-content">
    <div id="alerts-container"></div>
    <div class="buttons">
        <button class="btn-add" onclick="openIt()">Commande</button>
    </div>

    <div class="filtration">
        <div class="tt">
            <label for="filterSupplier">Filtrer par fournisseur:</label>
            <select id="filterSupplier" onchange="filterCommande2()">
                <option value="all">Tous les fournisseurs</option>
                <?php
        $fournisseurs = getFournisseur(); // Remplacez cette ligne par la fonction pour obtenir la liste des fournisseurs
        foreach ($fournisseurs as $fournisseur) {
            echo "<option value='{$fournisseur['id']}' id='fournisseur-filter'>{$fournisseur['nom']}</option>";
        }
    ?>
            </select>
        </div>

        <div class="tt">
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
        </div>




        <div class="tt">
            <label for="filterStatus">Filtrer par statut:</label>
            <select id="filterStatus" onchange="filterCommande()">
                <option value="all">Tous</option>
                <option value="1">Confirmer</option>
                <option value="0">En Cours</option>
                <option value="-1">Annuler</option>
            </select>
        </div>



    </div>










    <div class="overview-boxes ">

        <div class="box-add3" id='popup'>
            <form class="facture_design" action="../model/ajoutercommande.php" method="post">


                <div class="entete_facture">
                    <div class="left">
                        <label for="date_commande">Date Commande</label>
                        <input type="date" name="date_commande" id="date_commande" required>


                        <label>Numero Commande</label>
                        <input type="text" name="Num_commande"
                            value="<?= isset($_GET['Num_commande']) ? $_GET['Num_commande'] : '' ?>" id="Num_commande"
                            placeholder="Numero Commande" required>
                    </div>
                    <div class="right">
                        <button id="closePopupButton" onclick="closePopup()"><i class='bx bx-x'></i></button>
                        <label for="id_fournisseur">Fournisseur</label>
                        <select type="text" name="id_fournisseur" id="id_fournisseur" required
                            onchange="updateProduitsList(this.value)">
                            <option value="all">Tous les fournisseurs</option>
                            <?php
                                $fournisseures=getFournisseur();
                                    foreach ($fournisseures as $fournisseure) {
                                       $fournisseureId = $fournisseure['id'];
                                         $fournisseureName = $fournisseure['nom'];
                                       $selected = (!empty($_GET['id']) && $fournisseure['id_fournisseur'] == $statueId) ? "selected" : "";
                                       echo "<option $selected value=\"$fournisseureId\">$fournisseureName</option>";
                            }
                            ?>
                        </select>


                        <input type="hidden" value="<?=!empty($_GET['id']) ? $_GET["id"]: ""?>" name="id_commande"
                            id="id_commande">

                    </div>

                </div>
                <div class="body_form">
                    <div class="table">
                        <table class='facture-form'>


                            <th>Designation</th>
                            <th>prix Unitaire</th>
                            <th>Quantite</th>
                            <th>prix global</th>
                            <tr>

                                <td>
                                    <select type="text" name="id_produit[]" id="designation" required
                                        onchange="updatePrixUnitaire(this)">

                                        <?php
        $produits = getArticle();
        foreach ($produits as $produit) {
            $produitId = $produit['id'];
            $produitName = $produit['designation'];
            $produitPrix = $produit['prix_unitaire'];
            $selected = (!empty($_GET['id']) && $_GET['id'] == $produitId) ? "selected" : "";
            echo "<option $selected value=\"$produitId\" data-prix=\"$produitPrix\">$produitName</option>";
        }
        ?>
                                    </select>
                                </td>

                                <td><input type="text" name="prix_unitaire[]" required
                                        onchange="calculatePrixGlobal(this)"></td>
                                <td><input type="text" name="quantite[]" required onchange="calculatePrixGlobal(this)">
                                </td>
                                <td><input type="text" name="prix_global[]" required readonly></td>


                                <td><button type="button" class="add_facture_button"
                                        onclick="addFactureRow()">Add</button>

                            </tr>
                            <tfoot>
                                <tr>

                                    <td>Total</td>
                                    <td><input type="text" id="total" name="total" required readonly></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>


                <div id="paid_amount_container" style="display: none;">
                    <label for="paid_amount">Paid Amount</label>
                    <input type="text" name="paid_amount" id="paid_amount">
                </div>
                </select>


                <button type="submit">Confirmer</button>
            </form>

        </div>






        <div class="box_commande">
            <table id="myDataTable">
                <thead>
                    <tr>
                        <th>Numero Commande</th>
                        <th>Date Commande</th>
                        <th>NIF Fournisseur</th>
                        <th>Statue Commande</th>
                        <th>Action</th>
                        <th>Confirmation</th>
                    </tr>

                </thead>

                <tbody>
                    <?php
                $commandes=getCommande();
       
  
                if(!empty($commandes)&&is_array($commandes)){
                    
                    foreach($commandes as $key =>$value){
                ?>
                    <tr class="commande-row" id_commande="<?=$value['id']?>"
                        data-status="<?= $value['statut_commande'] ?>" data-supplier="<?= $value['id_fournisseur']?>"
                        data-month="<?= date('n', strtotime($value['date_commande'])) ?>">
                        <input type="hidden" name="id_commande" value="<?= $value['id'] ?>">
                        <td><?=$value['Num_commande']?></td>
                        <td><?=$value['date_commande']?></td>

                        <td><?=$value['nif']?> </td>
                        <td id="statusCell<?= $value['id'] ?>" class="commande-cell"> <?php
                        if ($value['statut_commande'] == 1) {
                            echo 'Confirmer';
                        } elseif ($value['statut_commande'] == -1) {
                            echo 'Annuler';
                        } elseif ($value['statut_commande']== 0) {
                            echo 'En Cours';
                        }
                        ?>
                        </td>
                        <td>
                            <?php
                        // Vérifiez le statut de la commande et désactivez le bouton "Modifier" si nécessaire
                        if ($value['statut_commande'] == 1 || $value['statut_commande'] == -1) {
                            echo '<button class="modifier-commande-button disabled-button" disabled>Modifier</button>';
                        } else {
                            echo '<button class="modifier-commande-button"
                                  onclick="openEditPopupCommande('.$value['id'].')"
                                  data-id="'.$value['id'].'">Modifier</button>';
                        }
                        ?>
                        </td>
                        <td><button class="modifier-commande-button" type="button" onclick="showDetailsPopup(this)">
                                Details</button>
                        </td>
                        <div id="details-popup" class="popup">
                            <div class="popup-content">
                                <span class="close-popup" onclick="closeDetailsPopup()">&times;</span>
                                <h2>Command Details</h2>
                                <div class="command-details" id="command-details">



                                </div>

                                <div class="buttonsActions">
                                    <button class="confirm-button" data-id-commande="<?=$value['id']?>"
                                        onclick="confirmCommand(this)" onclick="confirmCommand(this)">Confirm

                                    </button>

                                    <button class="cancel-button" data-id-commande="<?=$value['id']?>"
                                        onclick="annulerCommand(this)">Cancel
                                    </button>
                                </div>
                    </tr>


                    <?php
             
                 }
                }
            
                ?>
                </tbody>




            </table>


        </div>
    </div>

</div>
</div>

































































<script>
$(document).ready(function() {
    $('#myDataTable').DataTable();
});
</script>


<script>
function showAlert(message, alertType) {

    const alertDiv = document.createElement('div');
    alertDiv.textContent = message;
    alertDiv.classList.add('alert', alertType);


    const alertsContainer = document.getElementById('alerts-container');
    alertsContainer.appendChild(alertDiv);

    // Supprimez l'alerte après un certain telmps
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}


function confirmCommand() {
    const confirmButton = document.querySelector('.confirm-button');
    const idCommande = confirmButton.getAttribute('data-id-commande'); // Utilisez 'data-id-commande'

    // Send an AJAX request to update the command status
    fetch(`../model/updatecommande.php?id=${idCommande}&status=1`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetch(`../model/ajouterFacture.php?orderId=${idCommande}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {

                            showAlert('Commande confirmée et facture ajoutée avec succès.');
                            location.reload();
                            closeDetailsPopup();
                        } else {
                            // Handle failure, e.g., show an error message
                            showAlert('Erreur lors de l\'ajout de la facture.');
                        }
                    })

            } else {
                // Handle failure, e.g., show an error message
                showAlert(data.message);
            }
        })
        .catch(error => {
            console.error(error);
            // Handle error, e.g., show an error message
            showAlert('An error occurred while confirming the command.');
        });
}

function annulerCommand(buttonElement) {
    // Utilisez buttonElement pour obtenir l'attribut id-commande
    const idCommande = buttonElement.getAttribute('data-id-commande');

    // Envoyer une requête AJAX pour mettre à jour le statut de la commande
    fetch(`../model/updatecommande.php?id=${idCommande}&status=-1`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour l'affichage ou effectuer d'autres actions
                // Par exemple, recharger la page pour voir le changement
                location.reload();
            } else {
                showAlert(data.message);
            }
        })
        .catch(error => {
            console.error(error);
            showAlert('Une erreur s\'est produite lors de l\'annulation de la commande.');
        });
}



function showDetailsPopup(buttonElement) {
    const row = buttonElement.closest('tr');
    const productTable = document.createElement('table');
    const idCommande = row.getAttribute('id_commande');
    const commandDetailsDiv = document.getElementById('command-details');
    const confirmButton = document.querySelector('.confirm-button');
    confirmButton.setAttribute('data-id-commande', idCommande);
    // Fetch command details using AJAX
    fetch(`../model/get_produit_commande.php?id_commande=${idCommande}`)
        .then(response => response.json())
        .then(data => {
            // Clear previous content
            commandDetailsDiv.innerHTML = '';
            const productEntete = document.createElement('thead');
            productEntete.innerHTML = `
              
                    <tr>
                     <th>Designation</th>
                     <th>Quantité</th>
                    <th>Prix Total</th>
                     </tr>
                
      `;
            commandDetailsDiv.appendChild(productEntete);
            data.forEach(product => {

                const productDetails = document.createElement('tbody');
                productDetails.innerHTML += `
                <tr>
                    <td>${product.designation}</td>
                    <td>${product.quantite}</td>
                    <td> ${product.montant_total}</td>
                </tr>
                      `;
                commandDetailsDiv.appendChild(productDetails);
            });

            commandDetailsDiv.appendChild(productTable);
            productTable.outerHTML += '</table>';
            // Display the popup
            const popup = document.getElementById('details-popup');
            popup.style.display = 'block';


        })
        .catch(error => {
            console.error(error);
        });
}

// Other functions like closeDetailsPopup and confirmCommand can remain unchanged


// Close the popup
function closeDetailsPopup() {
    const popup = document.getElementById('details-popup');
    popup.style.display = 'none';
}

const confirmLinks = document.querySelectorAll('.confirm-link');
// Attach a click event listener to each link
confirmLinks.forEach(link => {
    link.addEventListener('click', function(event) {
        event.preventDefault();

        // Show a confirmation dialog
        const confirmed = confirm("Are you sure you want to confirm this order?");

        // If the user confirms, proceed to update the status
        if (confirmed) {
            const orderId = this.getAttribute('data-id');

            // Send an AJAX request to update the status
            fetch('../model/updatecommande.php?id=' + orderId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('Commande confirmée et facture ajoutée avec succès.',
                            'alert-success');
                        window.location.href = '../model/ajouterfacture.php?orderId=' + orderId;
                    } else {
                        showAlert("Failed to update status.", 'alert-error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    showAlert("Failed to update status. Error: " + error, 'alert-error');
                });
        }
    });
});

// Add this function to calculate Prix global and update total
function calculatePrixGlobal(inputElement) {
    const row = inputElement.parentNode.parentNode;
    const quantiteInput = row.querySelector("input[name='quantite[]']");
    const prixUnitaireInput = row.querySelector("input[name='prix_unitaire[]']");
    const prixGlobalInput = row.querySelector("input[name='prix_global[]']");

    const quantite = parseFloat(quantiteInput.value);
    const prixUnitaire = parseFloat(prixUnitaireInput.value);

    if (!isNaN(quantite) && !isNaN(prixUnitaire)) {
        const prixGlobal = quantite * prixUnitaire;
        prixGlobalInput.value = prixGlobal.toFixed(1);

        updateTotal();
    }
}

// Add this function to update the total sum
function updateTotal() {
    const prixGlobalInputs = document.querySelectorAll("input[name='prix_global[]']");
    const totalInput = document.getElementById("total");

    let totalSum = 0;
    prixGlobalInputs.forEach(input => {
        const prixGlobal = parseFloat(input.value);
        if (!isNaN(prixGlobal)) {
            totalSum += prixGlobal;
        }
    });

    totalInput.value = totalSum.toFixed(2);
}

function addFactureRow() {
    const tableBody = document.querySelector('.facture-form tbody');
    const newRow = document.createElement('tr');

    newRow.innerHTML = `
        <td>
            <select type="text" name="id_produit[]" class="product-select" required onchange="updatePrixUnitaire(this)">
                <!-- Les options seront chargées dynamiquement via JavaScript -->
            </select>
        </td>
         <td><input type="text" name="prix_unitaire[]" required onchange="calculatePrixGlobal(this)"></td>
         <td><input type="text" name="quantite[]" required onchange="calculatePrixGlobal(this)">   </td>
         <td><input type="text" name="prix_global[]" required readonly></td>
        <td><button class="remove-facture-button" type="button" onclick="removeFactureRow(this)">Remove</button></td>
    `;

    tableBody.appendChild(newRow);

    // Mise à jour du select de produits dans la nouvelle ligne
    updateProduitsList(document.getElementById('id_fournisseur').value, newRow);
}


function updateProduitsList(fournisseurId, newRow = null) {
    const produitsSelect = newRow ? newRow.querySelector('.product-select') : document.getElementById(
        'designation');

    // Supprimer les options existantes
    produitsSelect.innerHTML = '';

    // Faire une requête AJAX pour obtenir les produits associés au fournisseur
    fetch(`../model/get_produits_by_fournisseur.php?fournisseur_id=${fournisseurId}`)
        .then(response => response.json())
        .then(data => {
            // Ajouter les options de produits au select
            data.forEach(produit => {
                const option = document.createElement('option');
                option.value = produit.id;
                option.textContent = produit.designation;
                option.setAttribute('data-prix', produit.prix_unitaire);
                produitsSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error(error);
        });
}


function updatePrixUnitaire(selectElement) {
    const row = selectElement.parentNode.parentNode;
    const prixUnitaireInput = row.querySelector("input[name='prix_unitaire[]']");

    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const prixUnitaire = parseFloat(selectedOption.getAttribute('data-prix'));


    prixUnitaireInput.value = isNaN(prixUnitaire) ? '' : prixUnitaire.toFixed(2);
    calculatePrixGlobal(prixUnitaireInput);
}

function removeFactureRow(buttonElement) {
    const row = buttonElement.closest('tr');
    row.remove();

    // Update the total after removing a row
    updateTotal();
}
</script>
<script src="../public/js/commande.js"></script>

<?php 
include "./footer.php";

?>