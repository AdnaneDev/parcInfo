<?php
include './header.php';
$categories = getCategorie();

if (!empty($_GET['id'])) {
    $item = getCategorie($_GET['id']);
}



function deleteCategorie($categorie_id) {
    
    $sql = "DELETE FROM leparc.categorie WHERE id=?";
    $req = $GLOBALS['connexion']->prepare($sql);
    $req->execute(array($categorie_id));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_categorie_id']) && !empty($_POST['delete_categorie_id'])) {
        $categorie_id = $_POST['delete_categorie_id'];
        deletecategorie($categorie_id);
    }
}

?>

<div class="home-content">
    <div class="buttons">
        <button class="btn-add" onclick="openIt()">Add</button>
    </div>

    <div class="overview-boxes">

        <div class="box-add3" id='popup'>
            <button id="closePopupButton" onclick="closePopup()"><i class='bx bx-x'></i></button>
            <form action="<?= !empty($_GET['id']) ? "../model/updatecategorie.php": "../model/ajoutercategorie.php"?>"
                method="post">


                <label for="intitule">Intitule</label>
                <input type="text" value="<?=!empty($_GET['id']) ? $item["intitule"] : ""?>" name="intitule"
                    id="intitule" placeholder="Veuillez saisir le Nom de la categorie">

                <input type="hidden" value="<?=!empty($_GET['id']) ? $item["id"]: ""?>" name="id" id="id">

                <button type='submit'>Validez</button>


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
        <div class="box_cate">
            <table id="myDataTable">
                <thead>
                    <tr>
                        <th>Nom De La categorie</th>
                        <th>Modifier</th>
                        <th>Supprimer</th>

                    </tr>

                </thead>
                <tbody>
                    <?php
                $items=getCategorie();
               
              
                if(!empty($items)&&is_array($items)){
                    
                    foreach($items as $key =>$value){
                ?>
                    <tr>
                        <td><?=$value['intitule']?></td>

                        <td onclick=" openIt();"><a href="?id=<?=$value['id']?>"> <i class=" bx bx-edit-alt"></i></a>
                        </td>
                        <td class="trash">
                            <form class="trash1" method="post"
                                onsubmit="return confirm('Are you sure you want to delete this product?');">
                                <input type="hidden" name="delete_categorie_id" value="<?= $value['id'] ?>">
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
    <?php
include './footer.php'
?>