(function($) {
    'use strict';

    $(document).ready(function() {
        $('#bukets-test-connection').on('click', function(e) {
            e.preventDefault();
            var $btn = $(this);
            var $result = $('#bukets-test-result');

            $btn.prop('disabled', true).text('Probando...');
            $result.html('');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'bukets_test_connection',
                    _wpnonce: bukets_ajax.nonce
                },
                success: function(response) {
                    $btn.prop('disabled', false).text('Probar Conexion');
                    var cls = response.success ? 'success' : 'error';
                    var msg = response.success ? 'Conexion exitosa' : 'Error de conexion';
                    $result.html('<div class="bukets-test-result ' + cls + '">' + msg + '</div>');
                },
                error: function() {
                    $btn.prop('disabled', false).text('Probar Conexion');
                    $result.html('<div class="bukets-test-result error">Error de conexion</div>');
                }
            });
        });
    });
})(jQuery);
