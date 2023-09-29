<?php
session_start();
$currentPage = basename($_SERVER['PHP_SELF']);
$role=$_SESSION["role"];

if(!isset($_SESSION["username"])){
    header("location:../vue/login.php");
}
include_once '../model/function.php' ;

?>
<!DOCTYPE html>

<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8" />
    <title> <?php
    echo ucfirst(str_replace(".php","",basename($_SERVER['PHP_SELF']))) ;
    ?></title>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


    <?php
if ($currentPage === 'produit.php') {
    echo '<link rel="stylesheet" href="../public/css/produit.css" />';
} elseif ($currentPage === 'fournisseur.php') {
    echo '<link rel="stylesheet" href="../public/css/fournisseur.css" />';
}elseif($currentPage==='commande.php'){
   echo'<link rel="stylesheet" href="../public/css/Commande.css" />';
}elseif($currentPage==='stock.php'){
   echo'<link rel="stylesheet" href="../public/css/stock.css" />';
}elseif($currentPage==='facture.php'){
   echo'<link rel="stylesheet" href="../public/css/facture.css" />';

}elseif($currentPage==='demande.php'){
   echo'<link rel="stylesheet" href="../public/css/demande.css" />';
}elseif($currentPage==='dashboard.php'){
   echo'<link rel="stylesheet" href="../public/css/dashboard.css" />';
}elseif($currentPage==='categorie.php'){
   echo'<link rel="stylesheet" href="../public/css/categorie.css" />';
}elseif($currentPage==='services.php'){
   echo'<link rel="stylesheet" href="../public/css/service.css" />';
}elseif($currentPage==='createuser.php'){
   echo'<link rel="stylesheet" href="../public/css/user.css" />';
}
?>






    <link rel="stylesheet" href="../public/css/style.css" />
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>

    <div class="sidebar">
        <div class="logo-details">
            <img src="../public/img/sona.png" alt="">
            <a href="./dashboard.php"> <span class="logo_name">SONATRACH</span></a>
        </div>
        <ul class="nav-links">
            <?php
if ($role === 1) {
    echo '<li><a href="./dashboard.php" class="' . (basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '') . '"><i class="bx bx-grid-alt"></i><span class="links_name">Dashboard</span></a></li>';
    echo '<li><a href="./produit.php" class="' . (basename($_SERVER['PHP_SELF']) === 'produit.php' ? 'active' : '') . '"><i class="bx bx-box"></i><span class="links_name">Produit</span></a></li>';
    echo '<li><a href="./fournisseur.php" class="' . (basename($_SERVER['PHP_SELF']) === 'fournisseur.php' ? 'active' : '') . '"><i class="bx bx-list-ul"></i><span class="links_name">Fournisseur</span></a></li>';
    echo '<li><a href="./demande.php" class="' . (basename($_SERVER['PHP_SELF']) === 'demande.php' ? 'active' : '') . '"><i class="bx bx-pie-chart-alt-2"></i><span class="links_name">Demande</span></a></li>';
    echo '<li><a href="./commande.php" class="' . (basename($_SERVER['PHP_SELF']) === 'commande.php' ? 'active' : '') . '"><i class="bx bx-pie-chart-alt-2"></i><span class="links_name">Commande</span></a></li>';
    echo '<li><a href="./facture.php" class="' . (basename($_SERVER['PHP_SELF']) === 'facture.php' ? 'active' : '') . '"><i class="bx bx-book-alt"></i><span class="links_name">Facture</span></a></li>';
    echo '<li><a href="./stock.php" class="' . (basename($_SERVER['PHP_SELF']) === 'stock.php' ? 'active' : '') . '"><i class="bx bx-book-content"></i><span class="links_name">Stock</span></a></li>';
    echo '<li><a href="./categorie.php" class="' . (basename($_SERVER['PHP_SELF']) === 'categorie.php' ? 'active' : '') . '"><i class="bx bx-book-alt"></i><span class="links_name">Categorie</span></a></li>';
    echo '<li><a href="./services.php" class="' . (basename($_SERVER['PHP_SELF']) === 'services.php' ? 'active' : '') . '"><i class="bx bx-book-content"></i><span class="links_name">Services</span></a></li>';
    echo '<li><a href="./createuser.php" class="' . (basename($_SERVER['PHP_SELF']) === 'createuser.php' ? 'active' : '') . '"><i class="bx bx-book-content"></i><span class="links_name">Create User</span></a></li>';
    echo '<li class="log_out"><a href="../model/disconnect.php"><i class="bx bx-log-out"></i><span class="links_name">Déconnexion</span></a></li>';
} elseif ($role === 4) {
    echo '<li><a href="./dashboard.php" class="' . (basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '') . '"><i class="bx bx-grid-alt"></i><span class="links_name">Dashboard</span></a></li>';
    echo '<li><a href="./demande.php" class="' . (basename($_SERVER['PHP_SELF']) === 'demande.php' ? 'active' : '') . '"><i class="bx bx-pie-chart-alt-2"></i><span class="links_name">Demande</span></a></li>'; 
    echo '<li><a href="./produit.php" class="' . (basename($_SERVER['PHP_SELF']) === 'produit.php' ? 'active' : '') . '"><i class="bx bx-box"></i><span class="links_name">Produit</span></a></li>';
    echo '<li class="log_out"><a href="../model/disconnect.php"><i class="bx bx-log-out"></i><span class="links_name">Déconnexion</span></a></li>';
            } 
elseif ($role === 5) {
    echo '<li><a href="./dashboard.php" class="' . (basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '') . '"><i class="bx bx-grid-alt"></i><span class="links_name">Dashboard</span></a></li>';
    echo '<li><a href="./demande.php" class="' . (basename($_SERVER['PHP_SELF']) === 'demande.php' ? 'active' : '') . '"><i class="bx bx-pie-chart-alt-2"></i><span class="links_name">Demande</span></a></li>'; 
    echo '<li><a href="./produit.php" class="' . (basename($_SERVER['PHP_SELF']) === 'produit.php' ? 'active' : '') . '"><i class="bx bx-box"></i><span class="links_name">Produit</span></a></li>';
    echo '<li><a href="./fournisseur.php" class="' . (basename($_SERVER['PHP_SELF']) === 'fournisseur.php' ? 'active' : '') . '"><i class="bx bx-list-ul"></i><span class="links_name">Fournisseur</span></a></li>';

 echo '<li><a href="./facture.php" class="' . (basename($_SERVER['PHP_SELF']) === 'facture.php' ? 'active' : '') . '"><i class="bx bx-book-alt"></i><span class="links_name">Facture</span></a></li>';

    echo '<li class="log_out"><a href="../model/disconnect.php"><i class="bx bx-log-out"></i><span class="links_name">Déconnexion</span></a></li>';
            } elseif ($role === 2) {
    echo '<li><a href="./dashboard.php" class="' . (basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '') . '"><i class="bx bx-grid-alt"></i><span class="links_name">Dashboard</span></a></li>';
    echo '<li><a href="./produit.php" class="' . (basename($_SERVER['PHP_SELF']) === 'produit.php' ? 'active' : '') . '"><i class="bx bx-box"></i><span class="links_name">Produit</span></a></li>';
    echo '<li><a href="./fournisseur.php" class="' . (basename($_SERVER['PHP_SELF']) === 'fournisseur.php' ? 'active' : '') . '"><i class="bx bx-list-ul"></i><span class="links_name">Fournisseur</span></a></li>';
    echo '<li><a href="./demande.php" class="' . (basename($_SERVER['PHP_SELF']) === 'demande.php' ? 'active' : '') . '"><i class="bx bx-pie-chart-alt-2"></i><span class="links_name">Demande</span></a></li>';
    echo '<li><a href="./commande.php" class="' . (basename($_SERVER['PHP_SELF']) === 'commande.php' ? 'active' : '') . '"><i class="bx bx-pie-chart-alt-2"></i><span class="links_name">Commande</span></a></li>';
    echo '<li><a href="./facture.php" class="' . (basename($_SERVER['PHP_SELF']) === 'facture.php' ? 'active' : '') . '"><i class="bx bx-book-alt"></i><span class="links_name">Facture</span></a></li>';
    echo '<li><a href="./stock.php" class="' . (basename($_SERVER['PHP_SELF']) === 'stock.php' ? 'active' : '') . '"><i class="bx bx-book-content"></i><span class="links_name">Stock</span></a></li>';
    echo '<li class="log_out"><a href="../model/disconnect.php"><i class="bx bx-log-out"></i><span class="links_name">Déconnexion</span></a></li>';
            } elseif ($role === 3 || $role === 6) {
                echo '<li><a href="./dashboard.php" class="' . (basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '') . '"><i class="bx bx-grid-alt"></i><span class="links_name">Dashboard</span></a></li>';
                
                echo '<li><a href="./demande.php" class="' . (basename($_SERVER['PHP_SELF']) === 'demande.php' ? 'active' : '') . '"><i class="bx bx-pie-chart-alt-2"></i><span class="links_name">Demande</span></a></li>';
              
               
               
                echo '<li class="log_out"><a href="../model/disconnect.php"><i class="bx bx-log-out"></i><span class="links_name">Déconnexion</span></a></li>';
            }
            ?>
        </ul>
    </div>
    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class="bx bx-menu sidebarBtn"></i>
                <span class="dashboard"></span>
            </div>
            <div class="page_name">
                <h3>
                    <?php
    echo ucfirst(str_replace(".php","",basename($_SERVER['PHP_SELF']))) ;
    ?>
                </h3>

            </div>


            <div class="profile-details">
                <!--<img src="images/profile.jpg" alt="">-->
                <span class="admin_name">
                    Salut,
                    <?php
        // Vérifiez d'abord si l'utilisateur est connecté avant d'afficher le nom d'utilisateur
       
        if (isset($_SESSION['username'])) {
            echo  $_SESSION['username'];
        } else {
            echo 'Guest'; // Affiche "Guest" si l'utilisateur n'est pas connecté
        }
        ?>
                </span>

            </div>
        </nav>