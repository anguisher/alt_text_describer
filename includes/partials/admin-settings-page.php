<?php
$message = '';
if( isset( $_POST['update_api_key'] ) ) {
    update_option( 'describer_api_key', $_POST['api_key'] );
    $message = 'API Key has been updated successfully.';
}
if (!empty($message)) {
    echo '<div class="updated"><p>' . $message . '</p></div>';
}
?>
<form method="post" action="">
    <label for="api_key">API Key:</label></br></br>
    <input style="width: 500px;" type="text" id="api_key" name="api_key" value="<?php echo get_option('describer_api_key'); ?>">
    <button type="submit" name="update_api_key" class="button">Update</button>
</form>
