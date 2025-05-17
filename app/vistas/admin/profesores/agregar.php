<?php require APPROOT . '/vistas/inc/header.php'; ?>

<h1 class="mb-4">Agregar Profesor</h1>

<form action="<?php echo URLROOT; ?>/admin/agregarProfesor" method="post">

    <div class="row">

        <div class="col-md-6">

            <div class="form-group">

                <label for="nombre">Nombre: <sup>*</sup></label>
                <input type="text" name="nombre" class="form-control <?php echo (!empty($datos['nombre_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $datos['nombre']; ?>">
                <span class="invalid-feedback"><?php echo $datos['nombre_err']; ?></span>

            </div>
        </div>
        <div class="col-md-6">

            <div class="form-group">

                <label for="apellido">Apellido: <sup>*</sup></label>
                <input type="text" name="apellido" class="form-control <?php echo (!empty($datos['apellido_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $datos['apellido']; ?>">
                <span class="invalid-feedback"><?php echo $datos['apellido_err']; ?></span>

            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-6">

            <div class="form-group">

                <label for="email">Email: <sup>*</sup></label>
                <input type="email" name="email" class="form-control <?php echo (!empty($datos['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $datos['email']; ?>">
                <span class="invalid-feedback"><?php echo $datos['email_err']; ?></span>

            </div>

        </div>

        <div class="col-md-6">

            <div class="form-group">

                <label for="telefono">Teléfono: <sup>*</sup></label>
                <input type="tel" name="telefono" class="form-control <?php echo (!empty($datos['telefono_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $datos['telefono']; ?>">
                <span class="invalid-feedback"><?php echo $datos['telefono_err']; ?></span>

            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-6">

            <div class="form-group">

                <label for="especialidad">Especialidad:</label>
                <select name="especialidad" class="form-control">
                    <option value="Tenis general" <?php echo ($datos['especialidad'] == 'Tenis general') ? 'selected' : ''; ?>>Tenis general</option>
                    <option value="Tenis infantil" <?php echo ($datos['especialidad'] == 'Tenis infantil') ? 'selected' : ''; ?>>Tenis infantil</option>
                    <option value="Tenis competición" <?php echo ($datos['especialidad'] == 'Tenis competición') ? 'selected' : ''; ?>>Tenis competición</option>
                    <option value="Preparación física" <?php echo ($datos['especialidad'] == 'Preparación física') ? 'selected' : ''; ?>>Preparación física</option>
                </select>

            </div>
        </div>

        <div class="col-md-6">

            <div class="form-group">

                <label for="nivel">Nivel:</label>

                <select name="nivel" class="form-control">
                    <option value="Principiante" <?php echo ($datos['nivel'] == 'Principiante') ? 'selected' : ''; ?>>Principiante</option>
                    <option value="Intermedio" <?php echo ($datos['nivel'] == 'Intermedio') ? 'selected' : ''; ?>>Intermedio</option>
                    <option value="Avanzado" <?php echo ($datos['nivel'] == 'Avanzado') ? 'selected' : ''; ?>>Avanzado</option>
                    <option value="Experto" <?php echo ($datos['nivel'] == 'Experto') ? 'selected' : ''; ?>>Experto</option>
                </select>

            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-6">

            <div class="form-group">

                <label for="precio_hora">Precio por hora (€):</label>
                <input type="number" name="precio_hora" class="form-control" value="<?php echo $datos['precio_hora']; ?>" step="0.01" min="0">

            </div>

        </div>
        <div class="col-md-6">

            <div class="form-group">

                <label class="d-block">&nbsp;</label>

                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="disponible" name="disponible" <?php echo $datos['disponible'] ? 'checked' : ''; ?>>
                    <label class="custom-control-label" for="disponible">Disponible para clases</label>
                </div>

            </div>
        </div>
    </div>

    <input type="submit" class="btn btn-primary" value="Agregar Profesor">

    <a href="<?php echo URLROOT; ?>/admin/profesores" class="btn btn-secondary">Cancelar</a>

</form>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>