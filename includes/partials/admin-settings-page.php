<?php
$message = '';
if (isset($_POST['update_api_key']) && wp_verify_nonce($_POST['api_key_nonce'], 'update_api_key_nonce')) {
    update_option('describer_api_key', $_POST['api_key']);
    $message = 'API Key has been updated successfully.';
}
if (!empty($message)) {
    echo '<div class="updated"><p>' . esc_html($message) . '</p></div>';
}
$nonce = wp_create_nonce('update_api_key_nonce');
?>
<form method="post" action="">
    <label for="api_key">API Key:</label><br><br>
    <input style="width: 500px;" type="text" id="api_key" name="api_key" value="<?php echo esc_attr(get_option('describer_api_key')); ?>">
    <input type="hidden" name="api_key_nonce" value="<?php echo $nonce; ?>">
    <button type="submit" name="update_api_key" class="button">Update</button>
</form>
