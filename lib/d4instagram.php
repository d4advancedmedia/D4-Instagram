<?php

// D4 Instagram Feed

//We want to direct traffic to our Instagram account and increase our number of followers by displaying a few photos from our Instagram account on our website.

// add the admin options page
add_action('admin_menu', 'd4instagram_page');
function d4instagram_page() {
add_options_page('D4 Instagram Options', 'D4 Instagram', 'manage_options', 'd4instagram', 'd4instagram_plugin_options');
}

// display the admin options page
function d4instagram_plugin_options() {
?>
<div>
<h2>D4 Instagram Feed</h2>
<h4>Step 1:</h4>
Set up application (uncheck OAuth redirect_uri): http://instagram.com/developer/clients/manage/#
<h4>Step 2:</h4>
Get User ID: http://jelled.com/instagram/lookup-user-id
<h4>Step 3:</h4>
Get Code: https://api.instagram.com/oauth/authorize/?client_id=CLIENT-ID&redirect_uri=REDIRECT-URI&response_type=code
<h4>Step 4: </h4>
Get Token: https://instagram.com/oauth/authorize/?client_id=CLIENT-ID&redirect_uri=REDIRECT-URI&response_type=token
<form action="options.php" method="post">
<?php settings_fields('d4instagram_options'); ?>
<?php do_settings_sections('d4instagram'); ?>
 
<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
</form></div>
 
<?php
}

// add the admin settings and such
add_action('admin_init', 'd4instagram_admin_init');
function d4instagram_admin_init(){
register_setting( 'd4instagram_options', 'd4instagram_options' );
add_settings_section('d4instagram_main', 'Application Settings', 'd4instagram_section_text', 'd4instagram');
add_settings_field('user_id', 'User ID', 'user_id_string', 'd4instagram', 'd4instagram_main');
add_settings_field('client_id', 'Client ID', 'client_id_string', 'd4instagram', 'd4instagram_main');
add_settings_field('client_secret', 'Client Secret', 'client_secret_string', 'd4instagram', 'd4instagram_main');
add_settings_field('code', 'Code', 'code_string', 'd4instagram', 'd4instagram_main');
add_settings_field('auth_token', 'Auth Token', 'auth_token_string', 'd4instagram', 'd4instagram_main');
}

function d4instagram_section_text() {
//echo '<p>Main description of this section here.</p>';
}

function user_id_string() {
$options = get_option('d4instagram_options');
echo "<input id='user_id_string' name='d4instagram_options[user_id]' size='40' type='text' value='{$options['user_id']}' />";
}

function client_id_string() {
$options = get_option('d4instagram_options');
echo "<input id='client_id_string' name='d4instagram_options[client_id]' size='40' type='text' value='{$options['client_id']}' />";
}

function client_secret_string() {
$options = get_option('d4instagram_options');
echo "<input id='client_secret_string' name='d4instagram_options[client_secret]' size='40' type='text' value='{$options['client_secret']}' />";
}

function code_string() {
$options = get_option('d4instagram_options');
echo "<input id='code_string' name='d4instagram_options[code]' size='40' type='text' value='{$options['code']}' />";
}

function auth_token_string() {
$options = get_option('d4instagram_options');
echo "<input id='auth_token_string' name='d4instagram_options[auth_token]' size='40' type='text' value='{$options['auth_token']}' />";
}

/* validate our options
function d4instagram_options_validate($input) {
$options = get_option('d4instagram_options');
$options['text_string'] = trim($input['text_string']);
if(!preg_match('/^[a-z0-9]{32}$/i', $options['text_string'])) {
$options['text_string'] = '';
}
return $options;
}*/

/*
Step 1: Set up application (uncheck OAuth redirect_uri): http://instagram.com/developer/clients/manage/#

Step 2: Get code: https://api.instagram.com/oauth/authorize/?client_id=CLIENT-ID&redirect_uri=REDIRECT-URI&response_type=code

Step 3: Get token:
https://instagram.com/oauth/authorize/?client_id=CLIENT-ID&redirect_uri=REDIRECT-URI&response_type=token
*/

// Use: [d4instagram id="" hashtag=""]
	function shortcode_d4instagram( $atts ) {
		$attr = shortcode_atts( array(
			'id'=>'',
			'hashtag'=>''
		), $atts );

		if ($attr['id'] != '') {
			$id = 'id="'.$attr['id'].'"';
		}
		if ($attr['hashtag'] != '') {
			$hashtag = $attr['hashtag'];
		}

		$options = get_option('d4instagram_options');

		$user_id = $options['user_id'];
		$client_id = $options['client_id'];
		$client_secret = $options['client_secret'];
		$code = $options['code'];
		$auth_token = $options['auth_token'];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.instagram.com/v1/users/{$user_id}/media/recent/?access_token={$auth_token}");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		$fetch = curl_exec($ch);
		curl_close($ch);

		$results = json_decode($fetch);

		//$response = json_decode($popular, true);
		$middles = '';
		//$middles = '<div style="display:none">'. print_r($results,true) .'</div>';
		$i = 0;
		foreach ( $results->data as $data ) {
			if(++$i > 8)
				break;
			$link = $data->link;
				$link = $data->images->standard_resolution->url;
			$caption = $data->caption->text;
			$author = $data->caption->from->username;
			$thumbnail = $data->images->thumbnail->url;
			//$dataPrint = print_r($data,true);
			//$rel = 'rel="shadowbox[instagram];'.$caption'"';
			$middles .= '<li><div class=hidden>'.$dataPrint.'</div><a class="thickbox" href="'. $link . '" target="_blank" '.$rel.' title="' . $caption . '"><img src="'.  $thumbnail .'" width="150" height="150"/></a></li>';
		}
		$output  = '';
		//$output .= '<div class="instagram-hashtag">#'.$hashtag.'</div>';
		$output .= '<div class="instagram-feed"><div class="page-wrapper"><h1>'.$hashtag.'</h1><h3>Follow Us On Instagram</h3><ul class="instagram-feed-list nobull">'. $middles . '</ul></div></div>';
		return $output;
	}

add_shortcode( 'd4instagram', 'shortcode_d4instagram' );	

?>