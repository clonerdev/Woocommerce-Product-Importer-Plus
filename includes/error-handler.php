<?php
function pip_log_error($message) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log($message);
    }
}
?>
