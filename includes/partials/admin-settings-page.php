<?php
$message = '';
if (isset($_POST['update_api_key']) && wp_verify_nonce($_POST['api_key_nonce'], 'update_api_key_nonce')) {
    update_option('describer_api_key', $_POST['api_key']);
    update_option('prisakaru_alt_describer_lang', $_POST['select_describer_language']);
    update_option('prisakaru_describer_on_upload', isset($_POST['generate_alternative_texts_on_upload']) ? 'true' : 'false');
    $message = 'Settings have been updated';
}
if (!empty($message)) {
    echo '<div class="updated"><p>' . esc_html($message) . '</p></div>';
}
$nonce = wp_create_nonce('update_api_key_nonce');
?>
<br><br>
<form method="post" action="">
    <label for="api_key">API Key:</label><br><br>
    <input style="width: 500px;" type="text" id="api_key" name="api_key" value="<?php echo esc_attr(get_option('describer_api_key')); ?>">
    <a href="https://prisakaru.lt/account">Don't have API key? Get it here</a>
    <br><br>
    <label for="select_describer_language">Please select language for alternative texts:</label><br><br>
    <select id="select_describer_language" name="select_describer_language">
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'English') echo 'selected'; ?> attr_lng="English">English</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Lithuanian') echo 'selected'; ?> attr_lng="Lithuanian">Lithuanian</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Spanish') echo 'selected'; ?> attr_lng="Spanish">Spanish</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'French') echo 'selected'; ?> attr_lng="French">French</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'German') echo 'selected'; ?> attr_lng="German">German</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Italian') echo 'selected'; ?> attr_lng="Italian">Italian</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Portuguese') echo 'selected'; ?> attr_lng="Portuguese">Portuguese</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Dutch') echo 'selected'; ?> attr_lng="Dutch">Dutch</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Swedish') echo 'selected'; ?> attr_lng="Swedish">Swedish</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Danish') echo 'selected'; ?> attr_lng="Danish">Danish</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Norwegian') echo 'selected'; ?> attr_lng="Norwegian">Norwegian</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Finnish') echo 'selected'; ?> attr_lng="Finnish">Finnish</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Icelandic') echo 'selected'; ?> attr_lng="Icelandic">Icelandic</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Greek') echo 'selected'; ?> attr_lng="Greek">Greek</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Turkish') echo 'selected'; ?> attr_lng="Turkish">Turkish</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Polish') echo 'selected'; ?> attr_lng="Polish">Polish</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Czech') echo 'selected'; ?> attr_lng="Czech">Czech</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Slovak') echo 'selected'; ?> attr_lng="Slovak">Slovak</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Hungarian') echo 'selected'; ?> attr_lng="Hungarian">Hungarian</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Romanian') echo 'selected'; ?> attr_lng="Romanian">Romanian</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Bulgarian') echo 'selected'; ?> attr_lng="Bulgarian">Bulgarian</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Croatian') echo 'selected'; ?> attr_lng="Croatian">Croatian</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Slovenian') echo 'selected'; ?> attr_lng="Slovenian">Slovenian</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Estonian') echo 'selected'; ?> attr_lng="Estonian">Estonian</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Latvian') echo 'selected'; ?> attr_lng="Latvian">Latvian</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Maltese') echo 'selected'; ?> attr_lng="Maltese">Maltese</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Arabic') echo 'selected'; ?> attr_lng="Arabic">Arabic</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Chinese') echo 'selected'; ?> attr_lng="Chinese">Chinese</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Japanese') echo 'selected'; ?> attr_lng="Japanese">Japanese</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Korean') echo 'selected'; ?> attr_lng="Korean">Korean</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Russian') echo 'selected'; ?> attr_lng="Russian">Russian</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Hindi') echo 'selected'; ?> attr_lng="Hindi">Hindi</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Bengali') echo 'selected'; ?> attr_lng="Bengali">Bengali</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Urdu') echo 'selected'; ?> attr_lng="Urdu">Urdu</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Punjabi') echo 'selected'; ?> attr_lng="Punjabi">Punjabi</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Telugu') echo 'selected'; ?> attr_lng="Telugu">Telugu</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Marathi') echo 'selected'; ?> attr_lng="Marathi">Marathi</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Tamil') echo 'selected'; ?> attr_lng="Tamil">Tamil</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Gujarati') echo 'selected'; ?> attr_lng="Gujarati">Gujarati</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Vietnamese') echo 'selected'; ?> attr_lng="Vietnamese">Vietnamese</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Thai') echo 'selected'; ?> attr_lng="Thai">Thai</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Indonesian') echo 'selected'; ?> attr_lng="Indonesian">Indonesian</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Malay') echo 'selected'; ?> attr_lng="Malay">Malay</option>
        <option <?php if(get_option('prisakaru_alt_describer_lang') == 'Filipino') echo 'selected'; ?> attr_lng="Filipino">Filipino</option>
    </select>
    <br><br>
    <input type="checkbox" id="generate_alternative_texts_on_upload" name="generate_alternative_texts_on_upload" <?php if(get_option('prisakaru_describer_on_upload') == 'true') echo 'checked'; ?>>
    <label for="generate_alternative_texts_on_upload">Generate alternative texts on image upload</label>
    <br><br>
    <input type="hidden" name="api_key_nonce" value="<?php echo $nonce; ?>">
    <button type="submit" name="update_api_key" class="button">Update</button>
</form>
