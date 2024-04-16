<div class="container is-fluid mb-6">
	<h1 class="title">Maestros</h1>
	<h2 class="subtitle">Lista de Maestros</h2>
</div>
<div class="container pb-6 pt-6">
    <?php
        use app\controllers\teacherController;

        $insTeacherList = new teacherController();
        
        echo $insTeacherList->listerTeacherController($url[1],15,$url[0],"");
    ?>
</div>
   