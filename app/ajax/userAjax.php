<?php
    require_once "../../config/app.php";
    require_once "../views/inc/session_start.php";
    require_once "../../autoload.php";

    use app\controllers\userController;

    if(isset($_POST['modulo_usuario'])){

        $insUser = new userController();

        if($_POST['modulo_usuario']=="registrar"){
            echo $insUser->registerUserController();
            
        }

    }else{
        session_destroy();
        header("location: ".APP_URL."login/");
    }