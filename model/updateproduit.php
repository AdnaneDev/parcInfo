<?php
include './connexion.php';

if (!empty($_POST['designation'])&&
!empty($_POST["ref"])&&
!empty($_POST["prix_unitaire"])&&
!empty($_POST["id_fournisseur"])&&
!empty($_POST["id_categorie"])&&
!empty($_POST['id'])
)


{
$sql="UPDATE leparc.produit SET designation=?, ref=?,prix_unitaire=?,id_fournisseur=?, id_categorie=?  WHERE id =?;";
$req=$connexion->prepare($sql);
$req->execute(array(
$_POST["designation"],
$_POST["ref"],
$_POST["prix_unitaire"],
$_POST["id_fournisseur"],
$_POST["id_categorie"],

$_POST["id"]
));

if( $req->rowCount()!=0){
$_SESSION['message']['text']='Matereil modifier avec succeses';
$_SESSION['message']['type']='succes';

}
else{
$_SESSION['message']['text']="rien a ete modifier";
$_SESSION['message']['type']='warning';
}


}
else{
$_SESSION['Message']['text']="une information manquante";
$_SESSION['Message']['type']='danger';
}
header('Location:../vue/produit.php');

?>