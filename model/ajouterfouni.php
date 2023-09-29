<?php
include './connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assurez-vous que toutes les données nécessaires ont été soumises
    if (
        !empty($_POST['id']) &&
        !empty($_POST['nom']) &&
        !empty($_POST['prenom']) &&
        !empty($_POST['rc']) &&
        !empty($_POST['nif']) &&
        !empty($_POST['nis']) &&
        !empty($_POST['rib']) &&
        !empty($_POST['email']) &&
        !empty($_POST['numero'])
    ) {
        $id = $_POST['id'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $rc = $_POST['rc'];
        $nif = $_POST['nif'];
        $nis = $_POST['nis'];
        $rib = $_POST['rib'];
        $email = $_POST['email'];
        $numero = $_POST['numero'];

        // Vérifiez d'abord si un fournisseur avec le même ID existe déjà
        $sql_check = "SELECT COUNT(*) AS count FROM leparc.fournisseur WHERE id = ?";
        $req_check = $connexion->prepare($sql_check);
        $req_check->execute([$id]);
        $result = $req_check->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            // Mise à jour d'un fournisseur existant
            $sql = "UPDATE leparc.fournisseur SET nom=?, prenom=?, rc=?, nif=?, nis=?, rib=?, email=?, numero=? WHERE id=?";
            $req = $connexion->prepare($sql);
            $req->execute([$nom, $prenom, $rc, $nif, $nis, $rib, $email, $numero, $id]);

            if ($req->rowCount() !== 0) {
                $_SESSION['message']['text'] = 'Fournisseur modifié avec succès';
                $_SESSION['message']['type'] = 'success';
                header('Location:../vue/fournisseur.php?message=success');
            } else {
                $_SESSION['message']['text'] = 'Erreur lors de la modification du fournisseur';
                $_SESSION['message']['type'] = 'danger';
                header('Location:../vue/fournisseur.php?message=danger');
               
            }
        } else {
            // Ajout d'un nouveau fournisseur
            $sql = "INSERT INTO leparc.fournisseur (id, nom, prenom, rc, nif, nis, rib, email, numero) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $req = $connexion->prepare($sql);
            $req->execute([$id, $nom, $prenom, $rc, $nif, $nis, $rib, $email, $numero]);

            if ($req->rowCount() !== 0) {
                 $_SESSION['message']['text'] = 'Fournisseur ajouté avec succès';
                 $_SESSION['message']['type'] = 'success';
                 header('Location:../vue/fournisseur.php?message=success');
            } else {
                $_SESSION['message']['text'] = 'Erreur lors de l\'ajout du fournisseur';
               $_SESSION['message']['type'] = 'danger';
               header('Location:../vue/fournisseur.php?message=danger');
            
            }
        }
    } else {
      
        header('Location:../vue/fournisseur.php');
    }
  
  
}

?>