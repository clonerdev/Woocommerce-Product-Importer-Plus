<?php
add_action('admin_menu', 'pip_admin_menu');
function pip_admin_menu() {
    add_menu_page(
        __('Product Importer Plus', 'product-importer-plus'),
        __('Product Importer', 'product-importer-plus'),
        'manage_options',
        'product-importer-plus',
        'pip_admin_page'
    );
}

function pip_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['pip_options'])) {
        update_option('pip_options', $_POST['pip_options']);
        echo '<div class="updated"><p>' . __('Settings saved.', 'product-importer-plus') . '</p></div>';
    }

    $options = get_option('pip_options', ['pip_product_links' => '']);

    ?>
    <div class="wrap">
        <h1><?php _e('Product Importer Plus', 'product-importer-plus'); ?></h1>
        <form method="post">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Product Links', 'product-importer-plus'); ?></th>
                    <td>
                        <textarea name="pip_options[pip_product_links]" rows="10" cols="50" class="large-text"><?php echo esc_textarea($options['pip_product_links']); ?></textarea>
                        <p class="description"><?php _e('Enter one product link per line.', 'product-importer-plus'); ?></p>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'product-importer-plus'); ?>" />
            </p>
        </form>
    </div>
    <?php
}
?>
