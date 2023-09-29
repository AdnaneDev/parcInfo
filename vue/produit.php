<?php
include './header.php';
$categories = getCategorie(); 
if(!empty($_GET['id'])){
    $item=getArticle($_GET['id']);  
}
$productList = getArticle();
function deleteProduct($product_id) {
    
    $sql = "DELETE FROM leparc.produit WHERE id=?";
    $req = $GLOBALS['connexion']->prepare($sql);
    $req->execute(array($product_id));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_product_id']) && !empty($_POST['delete_product_id'])) {
        $product_id = $_POST['delete_product_id'];
        deleteProduct($product_id);
    }
}
$fournisseurlist = getFournisseur();
?>

<div class="home-content">
    <div class="buttons">
        <button class="btn-add" onclick="openIt()">Add</button>
        <button class="btn-add" onclick="openImportPopup()">Importer</button>

    </div>
    <div class="box-add1" id='popup'>
        <button id="closePopupButton" onclick="closePopup()"><i class='bx bx-x'></i></button>
        <form action="../model/ajouterproduit.php" method="post">


            <label for="designation">Designation</label>
            <input type="text" value="<?=!empty($_GET['id']) ? $item["designation"] : ""?>" name="designation"
                id="designation" placeholder="Veuillez saisir la designation">

            <input type="hidden" value="<?=!empty($_GET['id']) ? $item["id"]: ""?>" name="id" id="id">

            <label for="Referance">Referance</label>
            <input type="text" value="<?=!empty($_GET['id']) ? $item["ref"] : ""?>" name="ref" id="ref"
                placeholder="Veuillez saisir la Reference">

            <label for="categorie">Categorie</label>
            <select name="id_categorie" value="<?=!empty($_GET['id']) ? $item["id_categorie"] : ""?>" id="categorie">
                <?php
                    foreach ($categories as $category) {
                        $categoryId = $category['id'];
                        $categoryName = $category['intitule'];
                        $selected = (!empty($_GET['id']) && $item['id_categorie'] == $categoryId) ? "selected" : "";
                        echo "<option $selected value=\"$categoryId\">$categoryName</option>";
                    }
                    ?>
            </select>

            <label for="prix_unitaire">Prix_unitaire</label>
            <input type="text" value="<?=!empty($_GET['id']) ? $item["prix_unitaire"] : ""?>" name="prix_unitaire"
                id="prix_unitaire" placeholder="Veuillez saisir le Prix Unitaire">
            <label for="id_fournisseur">Founisseur ID</label>
            <select type="text" name="id_fournisseur" id="id_fournisseur"
                value="<?=!empty($_GET['id']) ? $fournisseure["nom"] : ""?> " required>
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
            <button class="center-button2" type='submit'>Validez</button>


            <?php
                    if(!empty($_SESSION['message']['text'])){     
                     ?>
            <div class="alert <?= $_SESSION['message']['type']?>">
                <?=  $_SESSION['message']['text']?>
            </div>
            <?php
                   }
                
                ?>
        </form>
    </div>
    <div class="overview-boxes">
        <div class="popup" id="importPopup" style="display: none;">
            <div class="popup-content1">
                <h2>Importer Liste des Produits</h2>
                <form action="../model/importproduits.php" method="post" enctype="multipart/form-data">
                    <label for="file">Sélectionner le fichier Excel :</label>
                    <input type="file" name="file" id="file" accept=".xls, .xlsx" required>
                    <div class="y">
                        <button type="submit">Importer</button>
                        <button id="cancelImportButton" onclick="closeImportPopup()">Annuler</button>
                    </div>

                </form>

            </div>
        </div>

        <div class="box_produit">
            <table id="myDataTable">
                <thead>
                    <tr>
                        <th>ID Produit</th>
                        <th>Designation</th>
                        <th>Referance</th>
                        <th>Categorie</th>
                        <th>Modifier</th>
                        <th>Supprimer</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                $items=getArticle();
               
              
                if(!empty($items)&&is_array($items)){
                    
                    foreach($items as $key =>$value){
                ?>
                    <tr>
                        <td><?=$value['id']?></td>
                        <td><?=$value['designation']?></td>
                        <td><?=$value['ref']?></td>
                        <td><?=$value['categorie_nom']?></td>

                        <td class="center-button"><a href="javascript:void(0);"
                                onclick="openEditPopup(<?= $value['id'] ?>);">
                                <i class=" bx bx-edit-alt"></i></a>
                        </td>

                        <td class="trash">
                            <form class="trash1" method="post"
                                onsubmit="return confirm('Are you sure you want to delete this product?');">
                                <input type="hidden" name="delete_product_id" value="<?= $value['id'] ?>">
                                <button type="submit" class="trash">
                                    <i class="bx bx-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>


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

    <script>
    function openImportPopup() {
        const importPopup = document.getElementById('importPopup');
        importPopup.style.display = 'block';
    }

    function closeImportPopup() {
        const importPopup = document.getElementById('importPopup');
        importPopup.style.display = 'none';
    }
    </script>

    <?php
include './footer.php'
?>