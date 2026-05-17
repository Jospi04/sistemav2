<?php
// Validar que las variables estén definidas
$usuariosList = $usuariosList ?? [];
?>

<!-- ALERTAS DE ÉXITO O ERROR -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert-success-panel" style="background-color: rgba(32, 59, 20, 0.06); border: 1px solid rgba(32, 59, 20, 0.2); border-radius: 12px; padding: 14px 20px; color: var(--success-color); font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 0.88rem;">
        <i class='bx bx-check-circle' style="font-size: 1.2rem;"></i>
        <span><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert-error-panel" style="background-color: rgba(239, 68, 68, 0.06); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 12px; padding: 14px 20px; color: #ef4444; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 0.88rem;">
        <i class='bx bx-error-circle' style="font-size: 1.2rem;"></i>
        <span><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
    </div>
<?php endif; ?>

<style>
    .user-manager-grid {
        display: grid;
        grid-template-columns: 360px 1fr;
        gap: 24px;
        align-items: start;
        margin-top: 8px;
    }

    @media (max-width: 900px) {
        .user-manager-grid {
            grid-template-columns: 1fr;
        }
    }

    .glass-form-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 24px;
        box-shadow: rgba(32, 59, 20, 0.03) 0px 8px 24px;
    }

    .form-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0 0 20px 0;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .input-field-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 16px;
    }

    .input-field-group label {
        font-family: 'Inter', sans-serif;
        font-size: 10px;
        font-weight: 700;
        color: var(--text-muted);
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .input-style-premium {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background-color: var(--bg-primary);
        color: var(--text-main);
        font-family: inherit;
        font-size: 0.9rem;
        outline: none;
        box-sizing: border-box;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .input-style-premium:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(32, 59, 20, 0.1);
    }

    .btn-create-user {
        background-color: var(--accent-color);
        color: var(--bg-primary);
        border: none;
        border-radius: 10px;
        width: 100%;
        padding: 12px;
        font-family: 'Inter', sans-serif;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
        margin-top: 8px;
    }

    .btn-create-user:hover {
        opacity: 0.95;
        transform: translateY(-1px);
    }

    /* Badges de Roles */
    .badge-role {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        text-transform: uppercase;
        display: inline-block;
    }

    .badge-role-admin {
        background-color: rgba(124, 58, 237, 0.06);
        color: #7c3aed;
        border: 1px solid rgba(124, 58, 237, 0.15);
    }

    .badge-role-operario {
        background-color: rgba(16, 185, 129, 0.06);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.15);
    }

    /* Botón eliminar */
    .btn-delete-user {
        color: #ef4444;
        background-color: rgba(239, 68, 68, 0.05);
        border: 1px solid rgba(239, 68, 68, 0.15);
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-delete-user:hover {
        background-color: #ef4444;
        color: #ffffff;
        border-color: #ef4444;
        transform: scale(1.05);
    }
</style>

<div class="user-manager-grid">

    <!-- COLUMNA 1: FORMULARIO CREAR VENDEDOR -->
    <div class="glass-form-card">
        <h3 class="form-title">
            <i class='bx bx-user-plus' style="color: var(--accent-color); font-size: 1.3rem;"></i>
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
            <div class="input-field-group" style="margin-bottom: 24px;">
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
    <section class="transactions-section" style="margin-top: 0;">
        <div class="section-title-bar">
            <h2>Vendedores y Administradores Registrados</h2>
            <span class="badge-online" style="background-color: rgba(32, 59, 20, 0.06); color: var(--success-color); border: 1px solid rgba(32, 59, 20, 0.15); font-weight: 700; font-size: 0.72rem; padding: 4px 8px; border-radius: 4px;">Cuentas Activas</span>
        </div>

        <div class="table-wrapper" style="max-height: 480px; overflow-y: auto;">
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
                                <td class="font-mono font-bold" style="color: var(--text-muted);">#<?php echo $usr['id']; ?></td>
                                <td class="font-bold" style="color: var(--text-main);"><?php echo htmlspecialchars($usr['nombre']); ?></td>
                                <td class="font-mono text-accent" style="font-size: 0.85rem;"><?php echo htmlspecialchars($usr['usuario']); ?></td>
                                <td>
                                    <?php if ($usr['rol'] === 'admin'): ?>
                                        <span class="badge-role badge-role-admin">Administrador</span>
                                    <?php else: ?>
                                        <span class="badge-role badge-role-operario">Grifero</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="display: flex; justify-content: center;">
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
<div id="deleteConfirmModal" class="delete-modal-overlay" style="display: none;">
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

<style>
    /* Modal de Confirmación Premium */
    .delete-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        animation: fadeInOverlay 0.25s ease forwards;
        box-sizing: border-box;
    }

    .delete-modal-box {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 32px;
        width: 100%;
        max-width: 400px;
        box-shadow: rgba(0, 0, 0, 0.1) 0px 20px 40px;
        text-align: center;
        box-sizing: border-box;
        transform: translateY(20px);
        animation: slideInBox 0.3s cubic-bezier(0.1, 0.8, 0.2, 1) forwards;
    }

    @keyframes fadeInOverlay {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideInBox {
        to { transform: translateY(0); }
    }

    .delete-modal-icon {
        width: 56px;
        height: 56px;
        background-color: rgba(239, 68, 68, 0.08);
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: #ef4444;
        font-size: 2.2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px auto;
    }

    .delete-modal-title {
        font-family: 'Inter', sans-serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0 0 10px 0;
    }

    .delete-modal-text {
        font-family: 'Inter', sans-serif;
        font-size: 0.85rem;
        color: var(--text-muted);
        line-height: 1.5;
        margin: 0 0 24px 0;
    }

    .delete-modal-actions {
        display: flex;
        gap: 12px;
    }

    .btn-delete-cancel {
        flex: 1;
        padding: 12px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background-color: var(--bg-primary);
        color: var(--text-main);
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-delete-cancel:hover {
        background-color: rgba(0,0,0,0.02);
    }

    .btn-delete-confirm {
        flex: 1;
        padding: 12px;
        border-radius: 10px;
        border: none;
        background-color: #ef4444;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .btn-delete-confirm:hover {
        background-color: #dc2626;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
    }
</style>

<script>
function openDeleteModal(event, deleteUrl, nombreUsuario) {
    event.preventDefault();
    const modal = document.getElementById('deleteConfirmModal');
    const title = document.getElementById('deleteModalTitle');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    
    title.textContent = `¿Eliminar a ${nombreUsuario}?`;
    confirmBtn.href = deleteUrl;
    modal.style.display = 'flex';
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteConfirmModal');
    modal.style.display = 'none';
}
</script>
