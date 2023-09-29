<?php
include 'connexion.php';


function getCategorie($categorie_id = null) {
    $sql = "SELECT * FROM leparc.categorie";
    
    if ($categorie_id !== null) {
        $sql .= " WHERE id = ?";
        $req = $GLOBALS['connexion']->prepare($sql);
        $req->execute([$categorie_id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    } else {
        $req = $GLOBALS['connexion']->prepare($sql);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}
function getServices($service_id = null) {
    $sql = "SELECT * FROM leparc.service";
    
    if ($service_id !== null) {
        $sql .= " WHERE id = ?";
        $req = $GLOBALS['connexion']->prepare($sql);
        $req->execute([$service_id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    } else {
        $req = $GLOBALS['connexion']->prepare($sql);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}
function getSalle($salle_id = null) {
    $sql = "SELECT * FROM leparc.salle";
    
    if ($salle_id !== null) {
        $sql .= " WHERE id = ?";
        $req = $GLOBALS['connexion']->prepare($sql);
        $req->execute([$salle_id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    } else {
        $req = $GLOBALS['connexion']->prepare($sql);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}

function getArticle($id = null) {
if (!empty($id)) {
$sql = "SELECT * FROM leparc.produit WHERE id=?";
$req = $GLOBALS["connexion"]->prepare($sql);
$req->execute(array($id));
return $req->fetch(PDO::FETCH_ASSOC); 
}
 else {
$sql = "SELECT p.*, c.intitule AS categorie_nom FROM leparc.produit p INNER JOIN leparc.categorie c ON p.id_categorie =
c.id";
$req = $GLOBALS["connexion"]->prepare($sql);
$req->execute();
$items = $req->fetchAll(PDO::FETCH_ASSOC);

return $items;
}
}




function getStock($id = null) {
if (!empty($id)) {
$sql = "SELECT * FROM leparc.stock WHERE id=?";
$req = $GLOBALS["connexion"]->prepare($sql);
$req->execute(array($id));
return $req->fetch(PDO::FETCH_ASSOC); 
}
 else {
$sql = "SELECT s.*,p.designation,p.id as id_produit
 FROM leparc.stock s INNER JOIN leparc.produit p on s.id_produit=p.id; ";
$req = $GLOBALS["connexion"]->prepare($sql);
$req->execute();
$items = $req->fetchAll(PDO::FETCH_ASSOC);

return $items;
}
}








function getFournisseur($id = null) {
if (!empty($id)) {
$sql = "SELECT * FROM leparc.fournisseur WHERE id=?";
$req = $GLOBALS["connexion"]->prepare($sql);
$req->execute(array($id));
return $req->fetch(PDO::FETCH_ASSOC); 
}
 else {
$sql = "SELECT *FROM leparc.fournisseur ";
$req = $GLOBALS["connexion"]->prepare($sql);
$req->execute();
$fournisseur = $req->fetchAll(PDO::FETCH_ASSOC);

return $fournisseur;
}
}
function getFacture($id = null) {
if (!empty($id)) {
$sql = "SELECT * FROM leparc.facture WHERE id=?";
$req = $GLOBALS["connexion"]->prepare($sql);
$req->execute(array($id));
return $req->fetch(PDO::FETCH_ASSOC); 
}
 else {
    $sql = "SELECT f.*,fo.nom AS nom_fournisseur,fo.id AS id_fournisseur,s.etat_statue as statue FROM leparc.facture f 
     INNER JOIN leparc.fournisseur fo ON f.id_fournisseur = fo.id
     INNER JOIN leparc.statue s ON f.id_statue = s.id
     ORDER BY f.date_facture DESC";
$req = $GLOBALS["connexion"]->prepare($sql);
$req->execute();
$factures = $req->fetchAll(PDO::FETCH_ASSOC);

return $factures;
}
}
function getCommande($id = null) {
if (!empty($id)) {
$sql = "SELECT * FROM leparc.commande WHERE id=?";
$req = $GLOBALS["connexion"]->prepare($sql);
$req->execute(array($id));
return $req->fetch(PDO::FETCH_ASSOC); 
}
 else {
    $sql = "SELECT c.*,fo.nif AS nif, sc.statut AS statue
     FROM leparc.commande c
     
     INNER JOIN leparc.fournisseur fo ON c.id_fournisseur = fo.id
     INNER JOIN leparc.statuecommande sc ON c.statut_commande = sc.id
     ORDER BY `date_commande` DESC;
    ";
$req = $GLOBALS["connexion"]->prepare($sql);
$req->execute();
$commande = $req->fetchAll(PDO::FETCH_ASSOC);

return $commande;
}
}
function getDemande($id = null) {
    if (isset($_SESSION['id_user'])) {
        $idUtilisateurConnecte = $_SESSION['id_user'];
        
        if (!empty($id)) {
            $sql = "SELECT * FROM leparc.demande WHERE id=?";
            $req = $GLOBALS["connexion"]->prepare($sql);
            $req->execute(array($id));
            return $req->fetch(PDO::FETCH_ASSOC); 
        } else {
            $sql = "SELECT d.*, s.nom_statue, u.name, p.designation
                    FROM leparc.demande d
                    INNER JOIN leparc.user u ON d.id_user = u.id
                    INNER JOIN leparc.statue_demande s ON d.id_statue = s.id
                    INNER JOIN leparc.produit p ON d.id_produit = p.id
                    WHERE ";

            if ($_SESSION["role"] === 3 || $_SESSION['role'] === 2) {
             
                $sql .= "d.id_user = :idUtilisateurConnecte";
                $req = $GLOBALS["connexion"]->prepare($sql);
                $req->bindParam(':idUtilisateurConnecte', $idUtilisateurConnecte, PDO::PARAM_INT);
            } else {
                $sql = "SELECT d.*, s.nom_statue, u.name, p.designation
                FROM leparc.demande d
                INNER JOIN leparc.user u ON d.id_user = u.id
                INNER JOIN leparc.statue_demande s ON d.id_statue = s.id
                INNER JOIN leparc.produit p ON d.id_produit = p.id";
        
        $req = $GLOBALS["connexion"]->prepare($sql);
       
            }
          
            $req->execute();
            $commandes = $req->fetchAll(PDO::FETCH_ASSOC);

            return $commandes;
        }
    }
}


function getStatue($categorie_id = null) {
    $sql = "SELECT * FROM leparc.statue";
    
    if ($categorie_id !== null) {
        $sql .= " WHERE id = ?";
        $req = $GLOBALS['connexion']->prepare($sql);
        $req->execute([$categorie_id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    } else {
        $req = $GLOBALS['connexion']->prepare($sql);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}
function getStatueC($categorie_id = null) {
    $sql = "SELECT * FROM leparc.statuecommande";
    
    if ($categorie_id !== null) {
        $sql .= " WHERE id = ?";
        $req = $GLOBALS['connexion']->prepare($sql);
        $req->execute([$categorie_id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    } else {
        $req = $GLOBALS['connexion']->prepare($sql);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}
function getRole($role_id = null) {
    $sql = "SELECT * FROM leparc.role";
    
    if ($role_id !== null) {
        $sql .= " WHERE id = ?";
        $req = $GLOBALS['connexion']->prepare($sql);
        $req->execute([$role_id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    } else {
        $req = $GLOBALS['connexion']->prepare($sql);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}
function getUser($user_id = null) {
    $sql = "SELECT u.*,s.nom AS service_nom,sa.nom AS salle_nom, ro.description AS role 
    FROM leparc.user AS u 
    JOIN leparc.role AS ro ON u.id_role = ro.id 
    INNER JOIN leparc.salle sa ON u.id_salle = sa.id 
    INNER JOIN leparc.service s ON sa.id_service = s.id;
    ";
    
    if ($user_id !== null) {
        $sql .= " WHERE u.id = ?";
        $req = $GLOBALS['connexion']->prepare($sql);
        $req->execute([$user_id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    } else {
        $req = $GLOBALS['connexion']->prepare($sql);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}








// Fonction pour récupérer les demandes de l'utilisateur connecté
function getDemandesUtilisateur($idUtilisateurConnecte) {
    // Requête SQL pour récupérer les demandes de l'utilisateur connecté
    $sql = "SELECT d.quantite, d.date_demande, s.nom_statue AS etat, u.username, p.designation AS nom
            FROM leparc.demande d
            INNER JOIN leparc.user u ON d.id_user = u.id
            INNER JOIN leparc.statue_demande s ON d.id_statue = s.id
            INNER JOIN leparc.produit p ON d.id_produit = p.id
            WHERE d.id_user = :idUtilisateurConnecte";

    try {
        $stmt = $GLOBALS['connexion']->prepare($sql);
        $stmt->bindParam(':idUtilisateurConnecte', $idUtilisateurConnecte, PDO::PARAM_INT);
        $stmt->execute();
        $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $demandes;
    } catch (PDOException $e) {
        // En cas d'erreur de la base de données, vous pouvez gérer l'erreur ici
        return array('error' => 'Erreur de base de données : ' . $e->getMessage());
    }
}




?>