    <?php 

    $nom_server="localhost";
    $db_name="leparc";
    $user="root";
    $password="";
    try{
        $connexion=new PDO ("mysql:host=$nom_server;charset=utf8;port=4306,dbname=$db_name", $user, $password);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connexion;
    }
    catch (Exception $e){
        die("Erreur de connexion: ".$e->getMessage());
        
    } 
    ?>