<?php
include './header.php';
if(!empty($_GET['id'])){
    $item=getServices($_GET['id']);  
}

function deleteService($service_id) {
    
    $sql = "DELETE FROM leparc.service WHERE id=?";
    $req = $GLOBALS['connexion']->prepare($sql);
    $req->execute(array($service_id));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_service_id']) && !empty($_POST['delete_service_id'])) {
        $service_id = $_POST['delete_service_id'];
        deleteService($service_id);
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
            <form action="<?= !empty($_GET['id']) ? "../model/updateservice.php": "../model/ajouterservice.php"?>"
                method="post">


                <label for="designation">Nom Service</label>
                <input type="text" value="<?=!empty($_GET['id']) ? $item["nom"] : ""?>" name="nom" id="designation"
                    placeholder="Veuillez saisir le Nom de la categorie">

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
        <div class="box-service">
            <table id="myDataTable">
                <thead>
                    <tr>
                        <th>Nom Du Service</th>

                        <th>Modifier</th>
                        <th>Supprimer</th>

                    </tr>
                </thead>

                <tbody>
                    <?php
                $items=getServices();
               
              
                if(!empty($items)&&is_array($items)){
                    
                    foreach($items as $key =>$value){
                ?>
                    <tr>
                        <td><?=$value['nom']?></td>

                        <td onclick=" openIt();"><a href="?id=<?=$value['id']?>"> <i class=" bx bx-edit-alt"></i></a>
                        </td>
                        <td class="trash">
                            <form class="trash1" method="post"
                                onsubmit="return confirm('Are you sure you want to delete this Service?');">
                                <input type="hidden" name="delete_service_id" value="<?= $value['id'] ?>">
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