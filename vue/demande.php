<?php
include './header.php';
if(!empty($_GET['id'])){
    $item=getDemande($_GET['id']);  

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
    <div id="error-alert" class="alert alert-danger" style="display: none;">
        La quantité demandée n'est pas disponible en stock.
    </div>
    <div class="buttons">
        <button class="btn-add" onclick="openIt()">Demande</button>
    </div>

    <div class="overview-boxes ">

        <div class="box-add3" id='popup'>
            <form class="facture_design"
                action="<?= !empty($_GET['id']) ? "../model/updatedemande.php": "../model/ajouterdemande.php"?>"
                method="post">
                <div class="entete_facture">
                    <div class="left">
                        <label for="date_commande">Date Demande</label>
                        <input type="date" name="date_commande" id="date_commande" required>
                        <input type="hidden" value="<?=!empty($_GET['id']) ? $value["id"]: ""?>" name="id" id="id">

                    </div>
                    <div class="right">
                        <button id="closePopupButton" onclick="closePopup()"><i class='bx bx-x'></i></button>
                    </div>

                </div>
                <div class="body_form">
                    <div class="table">
                        <table class="facture-form">
                            <thead>
                                <tr>
                                    <th>Designation</th>
                                    <th>Quantite</th>
                                    <th>Cause</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="id_produit[]" id="designation" required>
                                            <?php
                            $stocks = getStock();
                            foreach ($stocks as $produit) {
                              $produitId = $produit['id_produit']  ;
                                $produitName = $produit['designation'];
                                $selected = (!empty($_GET['id']) && $_GET['id'] == $produitId) ? "selected" : "";
                                echo "<option $selected value=\"$produitId\">$produitName</option>";
                            }
                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="quantite[]" placeholder="Quantité" required>
                                    </td>
                                    <td>
                                        <textarea name="description[]" id="" cols="30" rows="4"></textarea>
                                    </td>

                                </tr>
                            </tbody>
                        </table>

                        <button type="submit">Confirm</button>
                    </div>
                </div>
        </div>





        </form>

    </div>
    <div class="box-demande">
        <table id="myDataTable">
            <thead>
                <tr>
                    <th>User</th>
                    <th>date demande</th>
                    <th>produit demande</th>
                    <th>Statue Commande</th>
                    <?php if ($_SESSION["role"] !== 3 && $_SESSION['role']!== 2) { ?>
                    <th>Detail Demande</th>

                    <?php }?>

                </tr>
            </thead>
            <tbody>
                <?php
                $commandes=getDemande();
       
  
                if(!empty($commandes)&&is_array($commandes)){
                    
                    foreach($commandes as $key =>$value){
                     
                ?>
                <tr>

                    <td><?=$value['name']?></td>
                    <td><?=$value['date_demande']?></td>
                    <td><?=$value['designation']?></td>

                    <td><?=$value['nom_statue']?> </td>
                    <?php if ($_SESSION["role"] !== 3 && $_SESSION['role']!== 2) { ?>
                    <td>
                        <button type="button" class="details-button center-button"
                            id="detailsModalButton<?= $value['id'] ?>" onclick="openDetailsPopup(<?= $value['id'] ?>)">
                            Details
                        </button>
                    </td>

                    <!-- Details Modal -->
                    <div id="detailsModal<?= $value['id'] ?>" class="modal" tabindex="-1" role="dialog"
                        aria-labelledby="detailsModalLabel">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailsModalLabel">Details</h5>
                                <button type="button" onclick="closeDetailsPopup(<?= $value['id'] ?>)" class="close"
                                    aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>



                            </div>
                            <div class="modal-body">
                                <p>Nom: <?= $value['name'] ?></p>
                                <p>Produit Demande: <?= $value['designation'] ?></p>
                                <p>Date de la demande: <?= $value['date_demande'] ?></p>
                                <p>Description: <?= $value['description'] ?></p>
                                <p>Quantite: <?= $value['quantite'] ?></p>

                            </div>
                            <div class="modal-footer">

                                <button type="button"
                                    class="btn btn-success  <?php echo ($value['id_statue'] === 1 || $value['id_statue'] === 3)  ? 'disabled-button' : '' ?>"
                                    name="status" value="1" onclick="confirmDemand(<?= $value['id']?>)">Confirm</button>

                                <button type="button"
                                    class="btn btn-danger <?php echo ($value['id_statue'] === 1 || $value['id_statue'] === 3)  ? 'disabled-button' : '' ?>"
                                    name="status" value="3" onclick="rejectDemand(<?= $value['id'] ?>)">Reject</button>

                            </div>
                        </div>
                    </div>


                    <?php 
       
            }?>



                </tr>


                <?php
      
                 }
                }
            
                ?>

            </tbody>
        </table>

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
            }, 3000);
        }

        function openDetailsPopup(demandId) {
            const modal = document.getElementById(`detailsModal${demandId}`);
            modal.style.display = 'block';
        }

        function closeDetailsPopup(demandId) {
            const modal = document.getElementById(`detailsModal${demandId}`);
            modal.style.display = 'none';
        }



        function confirmDemand(demandId) {
            if (confirm("Voulez-vous vraiment confirmer cette demande?")) {
                fetch('../model/updatedemande.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `demand_id=${demandId}&status=1`,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Demande confirmée avec succès.');
                            // Rafraîchir la page pour afficher les changements
                            window.location.reload();
                        } else {
                            alert('Quantite Introuvable');
                        }
                    })
                    .catch(error => {
                        console.error('Une erreur s\'est produite :', error);
                    });
            }
        }

        function rejectDemand(demandId) {
            if (confirm("Voulez-vous vraiment rejeter cette demande?")) {
                fetch('../model/updatedemande.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `demand_id=${demandId}&status=3`,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Demande rejetée avec succès.');
                            // Rafraîchir la page pour afficher les changements
                            window.location.reload();
                        } else {
                            alert('Quantite Introuvable');
                        }
                    })
                    .catch(error => {
                        console.error('Une erreur s\'est produite :', error);
                    });
            }
        }
        </script>

        <?php
include './footer.php'

?>