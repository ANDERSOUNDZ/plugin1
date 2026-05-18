<div class="wrap bukets-wrap">
    <h1><?php echo esc_html__('Bukets Contifico Sync — Dashboard', 'bukets-contifico'); ?></h1>

    <div class="bukets-status-grid">
        <div class="bukets-card">
            <h3><?php esc_html_e('API Contifico', 'bukets-contifico'); ?></h3>
            <div class="value <?php echo $apiOk ? 'status-ok' : 'status-error'; ?>">
                <?php echo $apiOk ? esc_html__('Conectado', 'bukets-contifico') : esc_html__('Desconectado', 'bukets-contifico'); ?>
            </div>
        </div>
        <div class="bukets-card">
            <h3><?php esc_html_e('Ultima Sincronizacion', 'bukets-contifico'); ?></h3>
            <div class="value" style="font-size:14px;"><?php echo esc_html($lastSync); ?></div>
        </div>
    </div>

    <p>
        <a href="<?php echo esc_url(admin_url('admin.php?page=bukets-ct-settings')); ?>" class="button button-primary">
            <?php esc_html_e('Configurar Credenciales', 'bukets-contifico'); ?>
        </a>
    </p>
</div>
