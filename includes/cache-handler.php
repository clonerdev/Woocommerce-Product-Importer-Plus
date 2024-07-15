<?php
function pip_get_cached_product($url) {
    $cache_key = 'pip_cache_' . md5($url);
    $cached_data = get_transient($cache_key);
    return $cached_data ? json_decode($cached_data, true) : false;
}

function pip_cache_product($url, $product_data) {
    $cache_key = 'pip_cache_' . md5($url);
    set_transient($cache_key, json_encode($product_data), HOUR_IN_SECONDS);
}
?>
