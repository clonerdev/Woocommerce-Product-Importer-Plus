<?php
function pip_create_custom_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'pip_products';

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        product_id bigint(20) NOT NULL,
        product_data longtext NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function pip_insert_product_to_db($product_data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pip_products';

    $wpdb->insert(
        $table_name,
        [
            'product_id' => $product_data['id'],
            'product_data' => maybe_serialize($product_data)
        ]
    );
}
?>
