<?php
include './header_login.php';


?>



<body class="align">
    <div class="grid align__item">
        <div class="register">
            <img src="../public/img/sona.png" alt="">
            <h2>Connexion</h2>
            <?php
            if (isset($_GET['error'])) {
                $errorMessage = urldecode($_GET['error']);
                echo '<div class="error-message">' . $errorMessage . '</div>';
            }?>
            <form action="../model/getuser.php" method="post" class="form">

                <div class="form__field">
                    <input type="text" placeholder="Username" name="username">
                </div>

                <div class="form__field">
                    <input type="password" placeholder="••••••••••••" name="password">
                </div>

                <div class="form__field">
                    <button type='submit'>Connexion</button>
                </div>

            </form>


        </div>



    </div>

    </div>

</body>