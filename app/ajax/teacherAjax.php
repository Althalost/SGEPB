<?php
    require_once "../../config/app.php";
    require_once "../../autoload.php";

    use app\controllers\teacherController;

    if(isset($_POST['modulo_maestro'])){

        $insTeacher = new teacherController();

        if($_POST['modulo_maestro']=="registrar"){
            echo $insTeacher->registerTeacherController();
            
        }

    }else{
       
    }