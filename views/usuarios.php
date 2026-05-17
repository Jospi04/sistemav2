<?php
// Validar que las variables estén definidas
$usuariosList = $usuariosList ?? [];
?>

<!-- ALERTAS DE ÉXITO O ERROR -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert-success-panel">
        <i class='bx bx-check-circle'></i>
        <span><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert-error-panel">
        <i class='bx bx-error-circle'></i>
        <span><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
    </div>
<?php endif; ?>

<div class="user-manager-grid">

    <!-- COLUMNA 1: FORMULARIO CREAR VENDEDOR -->
    <div class="glass-form-card">
        <h3 class="form-title">
            <i class='bx bx-user-plus'></i>
            Crear Nuevo Vendedor
        </h3>
        
        <form action="usuarios/crear" method="POST">
            <!-- Nombre Completo -->
            <div class="input-field-group">
                <label for="usr_nombre">Nombre Completo</label>
                <input type="text" name="nombre" id="usr_nombre" class="input-style-premium" placeholder="Ej: Juan Pérez" required>
            </div>

            <!-- Nombre de Usuario -->
            <div class="input-field-group">
                <label for="usr_usuario">Nombre de Usuario (Acceso)</label>
                <input type="text" name="usuario" id="usr_usuario" class="input-style-premium" placeholder="Ej: juan.perez" required>
            </div>

            <!-- Contraseña -->
            <div class="input-field-group">
                <label for="usr_pass">Contraseña de Ingreso</label>
                <input type="password" name="password" id="usr_pass" class="input-style-premium" placeholder="••••••••" required>
            </div>

            <!-- Rol del Usuario -->
            <div class="input-field-group select-group">
                <label for="usr_rol">Rol en el Sistema</label>
                <select name="rol" id="usr_rol" class="input-style-premium">
                    <option value="operario" selected>Grifero Despachador (Operador)</option>
                    <option value="admin">Administrador del Grifo</option>
                </select>
            </div>

            <!-- Botón de acción -->
            <button type="submit" class="btn-create-user">
                <i class='bx bx-save'></i> Registrar Vendedor
            </button>
        </form>
    </div>

    <!-- COLUMNA 2: TABLA DE OPERADORES Y AUDITORÍA -->
    <section class="transactions-section section-history-cargas">
        <div class="section-title-bar">
            <h2>Vendedores y Administradores Registrados</h2>
            <span class="badge-online">Cuentas Activas</span>
        </div>

        <div class="table-wrapper scrollable-table-box">
            <table class="recent-sales-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>Nombre Completo</th>
                        <th>Usuario Acceso</th>
                        <th>Rol Asignado</th>
                        <th style="width: 80px; text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usuariosList)): ?>
                        <tr>
                            <td colspan="5" class="table-empty">No se encontraron usuarios registrados en la base de datos.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($usuariosList as $usr): ?>
                            <tr>
                                <td class="font-mono font-bold id-cell">#<?php echo $usr['id']; ?></td>
                                <td class="font-bold name-cell"><?php echo htmlspecialchars($usr['nombre']); ?></td>
                                <td class="font-mono text-accent username-cell"><?php echo htmlspecialchars($usr['usuario']); ?></td>
                                <td>
                                    <?php if ($usr['rol'] === 'admin'): ?>
                                        <span class="badge-role badge-role-admin">Administrador</span>
                                    <?php else: ?>
                                        <span class="badge-role badge-role-operario">Grifero</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="actions-cell-wrapper">
                                        <a href="#" 
                                           class="btn-delete-user" 
                                           title="Eliminar usuario"
                                           onclick="openDeleteModal(event, '/usuarios/eliminar?id=<?php echo $usr['id']; ?>', '<?php echo htmlspecialchars($usr['nombre']); ?>')">
                                            <i class='bx bx-trash'></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

</div>

<!-- MODAL DE CONFIRMACIÓN DE ELIMINACIÓN PREMIUM -->
<div id="deleteConfirmModal" class="delete-modal-overlay">
    <div class="delete-modal-box">
        <div class="delete-modal-icon">
            <i class='bx bx-error-circle'></i>
        </div>
        <h4 class="delete-modal-title" id="deleteModalTitle">¿Eliminar Vendedor?</h4>
        <p class="delete-modal-text">Esta acción es permanente y eliminará por completo la cuenta del grifero del sistema.</p>
        <div class="delete-modal-actions">
            <button type="button" class="btn-delete-cancel" onclick="closeDeleteModal()">Cancelar</button>
            <a href="#" id="confirmDeleteBtn" class="btn-delete-confirm">Sí, Eliminar</a>
        </div>
    </div>
</div>
