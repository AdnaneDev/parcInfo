<?php
include './header.php';

if(!empty($_GET['id'])){
$item=getFournisseur($_GET['id']);
}
$fournisseurlist = getFournisseur();
function deleteFournisseur($fournisseur_id) {

$sql = "DELETE FROM leparc.fournisseur WHERE id=?";
$req = $GLOBALS['connexion']->prepare($sql);
$req->execute(array($fournisseur_id));

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
if (isset($_POST['delete_fournisseur_id']) && !empty($_POST['delete_fournisseur_id'])) {
$fournisseur_id = $_POST['delete_fournisseur_id'];
deleteFournisseur($fournisseur_id);
}
}

?>
<style>
/* Style pour la boîte d'alerte */
.alert {
    background-color: black;
    padding: 15px;
    z-index: 100000;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}

/* Style pour les messages de succès */
.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

/* Style pour les messages d'erreur */
.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

/* Style pour les messages d'avertissement */
.alert-warning {
    background-color: #fff3cd;
    border-color: #ffeeba;
    color: #856404;
}
</style>

<div class="home-content">

    <div class="buttons">
        <button class="btn-add" onclick="openIt()">Add</button>
    </div>

    <div class="overview-boxes">
        <?php
 
 if (isset($_GET['message']) && $_GET['message'] === 'success') {
    echo '<div id="success-message" class="alert alert-success">Succed  </div>';
    unset($_GET['message']);
}elseif(isset($_GET['message']) && $_GET['message'] === 'danger'){
    echo '<div id="success-message" class="alert alert-danger">Failed</div>';
    unset($_GET['message']);
}
 ?>
        <script>
        setTimeout(function() {
            var successMessage = document.getElementById("success-message");
            if (successMessage) {
                successMessage.style.display = "none";
                var newUrl = window.location.pathname;
                history.replaceState(null, null, newUrl);
            }
        }, 3000);
        </script>


        <div class=" box-add2" id='popup'>
            <button id="closePopupButton" onclick="closePopup()"><i class='bx bx-x'></i></button>
            <form action="../model/ajouterfouni.php" method="post">
                <h2 style="color:#dc3545;">Fournisseur:</h2>

                <label for="id">id</label>
                <input type="text" value="<?=!empty($_GET['id']) ? $item["id"] : ""?>" name="id" id="designation"
                    placeholder="Veuillez saisir le ID">

                <label for="nom">Nom</label>
                <input type="text" value="<?=!empty($_GET['id']) ? $item["nom"] : ""?>" name="nom" id="nom"
                    placeholder="Veuillez saisir le Nom">
                <label for="prenom">Prenom</label>
                <input value="<?= !empty($_GET['id']) ? $item['prenom'] : ""?>" type=" text" name="prenom" id="prenom"
                    placeholder="Veuillez saisir le Prenom">
                <label for="rc">Raison Social</label>
                <input value="<?= !empty($_GET['id']) ? $item['rc'] : ""?>" type=" text" name="rc" id="rc"
                    placeholder="Veuillez saisir la rs">
                <label for="nif">NIF</label>
                <input value="<?= !empty($_GET['id']) ? $item['nif'] : ""?>" type=" text" name="nif" id="nif"
                    placeholder="Veuillez saisir le nif">
                <label for="nis">NIS</label>
                <input value="<?= !empty($_GET['id']) ? $item['nis'] : ""?>" type=" text" name="nis" id="nis"
                    placeholder="Veuillez saisir le nis">
                <label for="rib">RIB</label>
                <input value="<?= !empty($_GET['id']) ? $item['rib'] : ""?>" type=" text" name="rib" id="rib"
                    placeholder="Veuillez saisir le rib">
                <label for="email">E-mail</label>
                <input value="<?= !empty($_GET['id']) ? $item['email'] : ""?>" type=" text" name="email" id="email"
                    placeholder="Veuillez saisir le email">
                <label for="numero">Numero</label>
                <input value="<?= !empty($_GET['id']) ? $item['numero'] : ""?>" type=" text" name="numero" id="numero"
                    placeholder="Veuillez saisir le numero">

                <button type='submit'>Validez</button>



            </form>
        </div>
        <div class="box_fourni">
            <table id="myDataTable">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Nom</th>
                        <th>Prenom</th>
                        <th>Raison social</th>
                        <th>NIF</th>
                        <th>NIS</th>
                        <th>RIB</th>
                        <th>Email</th>
                        <th>Numero</th>
                        <?php if ($_SESSION["role"] !== 3 && $_SESSION['role']!== 2) { ?>
                        <th>Modifier</th>
                        <th>Supprimer</th>
                        <?php }?>
                    </tr>

                </thead>
                <tbody>
                    <?php
                $fournisseurs=getFournisseur();
               
              
                if(!empty($fournisseurs)&&is_array($fournisseurs)){
                    
                    foreach($fournisseurs as $key =>$value){
                ?>
                    <tr>
                        <td><?=$value['id']?></td>
                        <td><?=$value['nom']?></td>
                        <td><?=$value['prenom']?></td>
                        <td><?=$value['rc']?></td>
                        <td><?=$value['nif']?></td>
                        <td><?=$value['nis']?></td>
                        <td><?=$value['rib']?></td>
                        <td><?=$value['email']?></td>

                        <td> <?=$value['numero']?></td>
                        <?php if ($_SESSION["role"] !== 3 && $_SESSION['role']!== 2) { ?>
                        <td> <a href="javascript:void(0);" onclick="openEditPopupFournisseur(<?= $value['id'] ?>);"> <i
                                    class=" bx bx-edit-alt"></i></a>
                        </td>

                        <td class="trash">
                            <form method="post"
                                onsubmit="return confirm('Voulez vous vraiment supprimer ce fournisseur?');">
                                <input type="hidden" name="delete_fournisseur_id" value="<?= $value['id'] ?>">
                                <button type="submit" class="trash">
                                    <i class="bx bx-trash-alt"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                    <?php }?>

                    <?php
                 }
                }
            
                ?>

                </tbody>

            </table>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('#myDataTable').DataTable();
    });
    </script>


    <?php
include './footer.php'
?>