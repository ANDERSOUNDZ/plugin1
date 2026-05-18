<div class="wrap">
    <h1><?php echo esc_html__('Bukets Contifico Sync — Configuracion', 'bukets-contifico'); ?></h1>

    <?php settings_errors('bukets_contifico'); ?>

    <form action="options.php" method="post">
        <?php
        settings_fields('bukets_contifico');
        do_settings_sections('bukets-ct-settings');
        submit_button(__('Guardar Configuracion', 'bukets-contifico'));
        ?>
    </form>

    <hr>

    <h2><?php esc_html_e('Probar Conexion', 'bukets-contifico'); ?></h2>
    <p>
        <button id="bukets-test-connection" class="button">
            <?php esc_html_e('Probar Conexion con Contifico', 'bukets-contifico'); ?>
        </button>
    </p>
    <div id="bukets-test-result"></div>
</div>
