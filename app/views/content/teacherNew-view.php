<div class="container is-fluid mb-6">
	<h1 class="title">Maestros</h1>
	<h2 class="subtitle">Nuevo Maestro</h2>
</div>

<div class="container pb-6 pt-6">

	<form class="FormAjax" action="<?php echo APP_URL; ?>app/ajax/teacherAjax.php" 
    method="POST" autocomplete="off" enctype="multipart/form-data" >

		<input type="hidden" name="modulo_maestro" value="registrar">

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombres</label>
				  	<input class="input" type="text" name="maestro_nombres" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Apellidos</label>
				  	<input class="input" type="text" name="maestro_apellidos" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required >
				</div>
		  	</div>
            <div class="column">
		    	<div class="control">
					<label>Telefono</label>
				  	<input class="input" type="text" name="maestro_telefono" pattern="[a-zA-Z0-9]{4,20}" maxlength="40" required >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Cedula de identidad</label>
				  	<input class="input" type="text" name="maestro_ci" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Email</label>
				  	<input class="input" type="email" name="maestro_email" maxlength="70" >
				</div>
		  	</div>
		</div>

		<div class="columns">
		  	<div class="column">
				<div class="file has-name is-boxed">
					<label class="file-label">
						<input class="file-input" type="file" name="maestro_foto" accept=".jpg, .png, .jpeg" >
						<span class="file-cta">
							<span class="file-label">
								Seleccione una foto
							</span>
						</span>
						<span class="file-name">JPG, JPEG, PNG. (MAX 5MB)</span>
					</label>
				</div>
		  	</div>
		</div>
		<p class="has-text-centered">
			<button type="reset" class="button is-link is-light is-rounded">Limpiar</button>
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
	</form>
</div>