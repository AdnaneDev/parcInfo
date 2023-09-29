<?php
include './header.php';
$roles=getRole();
$services=getServices();
$salles=getSalle();

if(!empty($_GET['id'])){
   $user=getUser($_GET["id"]);  
}

function deleteService($service_id) {
    
    $sql = "DELETE FROM leparc.user WHERE id=?";
    $req = $GLOBALS['connexion']->prepare($sql);
    $req->execute(array($service_id));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_user_id']) && !empty($_POST['delete_user_id'])) {
        $user_id = $_POST['delete_user_id'];
        deleteService($user_id);
    }
}

?>

</head>

<body>
    <div class="home-content">
        <div class="buttons">
            <button class="btn-add" onclick="openIt()">Add</button>
        </div>


        <div class="box-add3" id='popup'>
            <button id="closePopupButton" onclick="closePopup()"><i class='bx bx-x'></i></button>
            <form action="<?= !empty($_GET['id']) ? "../model/updateuser.php": "../model/ajouteruser.php"?>"
                method="post" enctype="multipart/form-data">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username"
                    value="<?=!empty($_GET['id']) ? $user["username"] :""?>" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password"
                    value="<?=!empty($_GET['id']) ? $user["password"] : ""?>" required>

                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password"
                    value="<?=!empty($_GET['id']) ? $user["password"] : ""?>" required>

                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?=!empty($_GET['id']) ? $user["name"] : ""?>" required>

                <label for=" lastname">Lastname:</label>
                <input type="text" id="lastname" name="lastname"
                    value="<?=!empty($_GET['id']) ? $user["last_name"] : ""?>" required>


                <label for="role">Role:</label>
                <select name="id_role" value="<?=!empty($_GET['id']) ? var_dump($user["id_role"]) : ""?>" id="role">
                    <?php
    foreach ($roles as $role) {
        $roleId = $role['id'];
        $roleName = $role['description'];
        $selected = (!empty($_GET['id']) && $user['id_role'] == $roleId) ? "selected" : "";
        echo "<option $selected value=\"$roleId\">$roleName</option>";
    }
    ?>
                </select>
                <label for="service">Service:</label>
                <select name="id_service" id="id_service">
                    <?php
    foreach ($services as $service) {
        $serviceId = $service['id'];
        $serviceName = $service['nom'];
        $selected = (!empty($_GET['id']) && $user['id_service'] == $serviceId) ? "selected" : "";
        echo "<option $selected value=\"$serviceId\">$serviceName</option>";
    }
    ?>
                </select>


                <label for="id_salle">Salle:</label>
                <select name="id_salle" id="id_salle">
                    <option value="<?=!empty($_GET['id']) ?$user["id_salle"] : ""?>">Sélectionnez une salle
                    </option>
                    <?php
    // Laissez ce champ vide pour l'instant, il sera rempli dynamiquement par JavaScript
    ?>
                </select>

                <script>
                // Code JavaScript pour mettre à jour le champ de sélection de la salle en fonction du service sélectionné
                document.getElementById('id_service').addEventListener('click', function() {
                    const selectedServiceId = this.value;
                    const salleSelect = document.getElementById('id_salle');

                    // Supprimez toutes les options actuelles
                    while (salleSelect.options.length > 0) {
                        salleSelect.remove(0);
                    }

                    // Si aucun service n'est sélectionné, quittez
                    if (!selectedServiceId) {
                        return;
                    }

                    // Chargez les salles associées à ce service à partir du serveur (vous devrez implémenter cette partie)
                    // Vous pouvez utiliser une requête AJAX pour récupérer les salles en fonction de l'ID du service
                    // Remplissez ensuite le champ de sélection de la salle avec les résultats

                    // Exemple de requête AJAX (assurez-vous d'ajuster les paramètres pour correspondre à votre serveur)
                    fetch('../model/salla.php?id_service=' + selectedServiceId)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(salle => {
                                const option = document.createElement('option');
                                option.value = salle.id;
                                option.text = salle.nom;
                                salleSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Erreur lors du chargement des salles :', error);
                        });
                });
                </script>



                <input type="submit" value="Create User">

        </div>
    </div>



    <div class="box-user">
        <table id="myDataTable">
            <thead>
                <tr>
                    <th>username</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Service</th>
                    <th>Salle</th>
                    <th>Role</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>

                </tr>
            </thead>
            <tbody>
                <?php
                $users=getUser();
               
              
                if(!empty($users)&&is_array($users)){
                    
                    foreach($users as $key =>$value){
                ?>
                <tr>
                    <td><?=$value['username']?></td>
                    <td><?=$value['name']?></td>
                    <td><?=$value['last_name']?></td>
                    <td><?=$value['service_nom']?></td>
                    <td><?=$value['salle_nom']?></td>
                    <td> <?=$value['role']?></td>
                    <td onclick=" openIt();">
                        <a href="javascript:void(0);" onclick="openEditUser(<?= $value['id'] ?>);"> <i
                                class=" bx bx-edit-alt"></i></a>
                    </td>

                    <td class="trash">
                        <form method="post" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            <input type="hidden" name="delete_user_id" value="<?= $value['id'] ?>">
                            <button class="trash" type="submit">
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
    </form>

    <script>
    $(document).ready(function() {
        $('#myDataTable').DataTable();
    });
    </script>

    <?php 
    include './footer.php'
    
    ?>