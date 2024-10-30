<?php
/*
Plugin Name: Kau-Boy's AutoCompleter
Plugin URI: http://kau-boys.de/24/wordpress/kau-boys-autocompleter-plugin
Description: Integrates a google suggest like search to your blog. It Prototype (Scriptaculous) or the jQuery to search for text within the title and/or the content.
Version: 3.1.3
Author: Bernhard Kau
Author URI: http://kau-boys.de
*/


define('AUTOCOMPLETER_URL', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)));
define('AUTOCOMPLETER_SEARCHFIELD_ID', get_option('kau-boys_autocompleter_id'));

function init_autocompleter() {
	load_plugin_textdomain( 'autocompleter', false, dirname( plugin_basename( __FILE__ ) ) );

	$custom_css = get_option( 'kau-boys_autocompleter_custom_css' );
	$framework = get_option( 'kau-boys_autocompleter_framework' );
	$searchfield_id = get_option( 'kau-boys_autocompleter_id' );
	$selectortype = get_option( 'kau-boys_autocompleter_selectortype' );
	// set default if values haven't been recieved from the database
	if(empty($searchfield_id)) $searchfield_id = 's';
	
	$autocompleter_options = array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'framework' => $framework,
		'searchfield_id' => $searchfield_id,
		'selectortype' => $selectortype,
		'lang' => $_REQUEST['lang']
	);

	if(!is_admin()){
		wp_enqueue_style( 'autocompleter', (empty($custom_css)? AUTOCOMPLETER_URL.'autocompleter.css' : $custom_css), null, null, 'screen' );
		if($framework == 'scriptaculous'){
			wp_enqueue_script('autocompleter', AUTOCOMPLETER_URL.'autocompleter.js', array( 'scriptaculous' ), null, true );
		} else {
			wp_enqueue_script( 'jquery_autocomplete', AUTOCOMPLETER_URL.'jquery.autocomplete.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'autocompleter', AUTOCOMPLETER_URL.'autocompleter.js', array( 'jquery' ), null, true );
		}
		wp_localize_script( 'autocompleter', 'Autocompleter', $autocompleter_options );
	}
}

function autocompleter_results() {
	global $wpdb; // this is how you get access to the database

	include( WP_PLUGIN_DIR . '/kau-boys-autocompleter/autocompleter_results.php' );

	die(); // this is required to return a proper result
}
add_action('wp_ajax_autocompleter_results', 'autocompleter_results');
add_action('wp_ajax_nopriv_autocompleter_results', 'autocompleter_results');

function autocompleter_admin_menu(){
	add_options_page("Kau-Boy's AutoCompleter settings", 'AutoCompleter', 8, __FILE__, 'autocompleter_admin_settings');	
}

function autocompleter_filter_plugin_actions($links, $file){
	static $this_plugin;
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
	
	if ($file == $this_plugin){
		$settings_link = '<a href="options-general.php?page=kau-boys-autocompleter/autocompleter.php">'.__('Settings').'</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}

function autocompleter_admin_settings(){
	if(isset($_POST['save'])){	
		update_option('kau-boys_autocompleter_framework', $_POST['kau-boys_autocompleter_framework']);
		update_option('kau-boys_autocompleter_id', $_POST['kau-boys_autocompleter_id']);
		update_option('kau-boys_autocompleter_selectortype', $_POST['kau-boys_autocompleter_selectortype']);
		update_option('kau-boys_autocompleter_choices', $_POST['kau-boys_autocompleter_choices']);
		update_option('kau-boys_autocompleter_custom_css', $_POST['kau-boys_autocompleter_custom_css']);
		update_option('kau-boys_autocompleter_encoding', $_POST['kau-boys_autocompleter_encoding']);
		update_option('kau-boys_autocompleter_searchfields', $_POST['kau-boys_autocompleter_searchfields']);
		update_option('kau-boys_autocompleter_resultfields', $_POST['kau-boys_autocompleter_resultfields']);
		update_option('kau-boys_autocompleter_titlelength', $_POST['kau-boys_autocompleter_titlelength']);
		update_option('kau-boys_autocompleter_contentlength', $_POST['kau-boys_autocompleter_contentlength']);
		$settings_saved = true;
	}
	$framework = get_option('kau-boys_autocompleter_framework');
	$searchfield_id = get_option('kau-boys_autocompleter_id');
	$selectortype = get_option('kau-boys_autocompleter_selectortype');
	$choices = get_option('kau-boys_autocompleter_choices');
	$custom_css = get_option('kau-boys_autocompleter_custom_css');
	$encoding = get_option('kau-boys_autocompleter_encoding');
	$searchfields = get_option('kau-boys_autocompleter_searchfields');
	$resultfields = get_option('kau-boys_autocompleter_resultfields');
	$titlelength = get_option('kau-boys_autocompleter_titlelength');
	$contentlength = get_option('kau-boys_autocompleter_contentlength');
	
	// set default if values haven't been recieved from the database
	if(empty($searchfield_id)) $searchfield_id = 's';
	if(empty($selectortype)) $selectortype = 'id';
	if(empty($choices)) $choices = 10;
	if(empty($encoding)) $encoding = 'UTF-8';
	if(empty($searchfields)) $searchfields = 'both';
	if(empty($resultfields)) $resultfields = 'both';
	if(empty($titlelength)) $titlelength = 50;
	if(empty($contentlength)) $contentlength = 120;
?>

<div class="wrap">
	<?php screen_icon(); ?>
	<h2>Kau-Boy's AutoCompleter</h2>
	<?php if($settings_saved) : ?>
	<div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
	<?php endif ?>
	<p>
		<?php _e('Here you can customize the plugin for your needs.', 'autocompleter') ?>
	</p>
	<form method="post" action="">
		<p>
			<label for="kau-boys_autocompleter_framework" style="width: 200px; display: inline-block;"><?php _e('JavaScript framework', 'autocompleter') ?></label>
			<select id="kau-boys_autocompleter_framework" name="kau-boys_autocompleter_framework" style="width: 147px;">
				<option<?php echo ($framework == 'jQuery')? ' selected="selected"' : '' ?>>jQuery</option>
				<option<?php echo ($framework == 'scriptaculous')? ' selected="selected"' : '' ?>>scriptaculous</option>
			</select>
			<span class="description"><?php _e('Here you can choose the JavaScript framework you prefer.', 'autocompleter') ?></span>
		</p>
		<p>
			<label for="kau-boys_autocompleter_selectortype" style="width: 200px; display: inline-block;"><?php _e('Selectortype', 'autocompleter') ?></label>
			<select id="kau-boys_autocompleter_selectortype" name="kau-boys_autocompleter_selectortype" style="width: 147px;">
				<option<?php echo ($selectortype == 'id')? ' selected="selected"' : '' ?>>id</option>
				<option<?php echo ($selectortype == 'name')? ' selected="selected"' : '' ?>>name</option>
			</select>
			<span class="description"><?php _e('Here you can set if you select the input by the id or name attribute (default = id).', 'autocompleter') ?></span>
		</p>
		<p>
			<label for="kau-boys_autocompleter_id" style="width: 200px; display: inline-block;"><?php _e('Search field attribute value', 'autocompleter') ?></label>
			<input type="text" id="kau-boys_autocompleter_id" name="kau-boys_autocompleter_id" value="<?php echo $searchfield_id ?>" />
			<span class="description"><?php _e('Here you can set the value of the id or name attribute from the search input used in your template (default = s).', 'autocompleter') ?></span>
		</p>
		<p>
			<label for="kau-boys_autocompleter_choices" style="width: 200px; display: inline-block;"><?php _e('Number of results', 'autocompleter') ?></label>
			<input type="text" id="kau-boys_autocompleter_choices" name="kau-boys_autocompleter_choices" value="<?php echo $choices ?>" />
			<span class="description"><?php _e('Here you can set the number of posts that will be shown.', 'autocompleter') ?></span>
		</p>
		<p>
			<label for="kau-boys_autocompleter_searchfields" style="width: 200px; display: inline-block;"><?php _e('Searchfields', 'autocompleter') ?></label>
			<select id="kau-boys_autocompleter_searchfields" name="kau-boys_autocompleter_searchfields" style="width: 147px;">
				<option value="post_title"<?php echo ($searchfields == 'post_title')? ' selected="selected"' : '' ?>><?php _e('Title only', 'autocompleter') ?></option>
				<option value="post_content"<?php echo ($searchfields == 'post_content')? ' selected="selected"' : '' ?>><?php _e('Content only', 'autocompleter') ?></option>
				<option value="both"<?php echo ($searchfields == 'both')? ' selected="selected"' : '' ?>><?php echo __('Title and Content', 'autocompleter') ?></option>
			</select>
			<span class="description"><?php _e('Here you can set where to search for the searchterm.', 'autocompleter') ?></span>
		</p>
		<p>
			<label for="kau-boys_autocompleter_resultfields" style="width: 200px; display: inline-block;"><?php _e('Resultfields', 'autocompleter') ?></label>
			<select id="kau-boys_autocompleter_resultfields" name="kau-boys_autocompleter_resultfields" style="width: 147px;">
				<option value="post_title"<?php echo ($resultfields == 'post_title')? ' selected="selected"' : '' ?>><?php _e('Title only', 'autocompleter') ?></option>
				<option value="both"<?php echo ($resultfields == 'both')? ' selected="selected"' : '' ?>><?php echo __('Title and Content', 'autocompleter') ?></option>
			</select>
			<span class="description"><?php _e('Here you can set which fields to show in the results list.', 'autocompleter') ?></span>
		</p>
		<p>
			<label for="kau-boys_autocompleter_titlelength" style="width: 200px; display: inline-block;"><?php _e('Title length', 'autocompleter') ?></label>
			<input type="text" id="kau-boys_autocompleter_titlelength" name="kau-boys_autocompleter_titlelength" value="<?php echo $titlelength ?>" />
			<span class="description"><?php _e('Here you can set the maximum length of the title in the results (default = 50).', 'autocompleter') ?></span>
		</p>
		<p>
			<label for="kau-boys_autocompleter_contentlength" style="width: 200px; display: inline-block;"><?php _e('Content length', 'autocompleter') ?></label>
			<input type="text" id="kau-boys_autocompleter_contentlength" name="kau-boys_autocompleter_contentlength" value="<?php echo $contentlength ?>" />
			<span class="description"><?php _e('Here you can set the maximum length of the content in the results (default = 120).', 'autocompleter') ?></span>
		</p>
		<p>
			<label for="kau-boys_autocompleter_encoding" style="width: 200px; display: inline-block;"><?php _e('Multibyte encoding', 'autocompleter') ?></label>
			<input type="text" id="kau-boys_autocompleter_encoding" name="kau-boys_autocompleter_encoding" value="<?php echo $encoding ?>" />
			<span class="description"><?php _e('Here you can set the encoding for the multibyte functions (default = UTF-8).', 'autocompleter') ?></span>
		</p>
		<p>
			<label for="kau-boys_autocompleter_custom_css" style="width: 200px; display: inline-block;"><?php _e('Path to custom css', 'autocompleter') ?></label>
			<input type="text" id="kau-boys_autocompleter_custom_css" name="kau-boys_autocompleter_custom_css" value="<?php echo $custom_css ?>" />
			<span class="description"><?php _e('Here you can set the path to a custom css file.', 'autocompleter') ?></span>
			<p class="description"><?php echo sprintf(__('Just use the <a href="%1$sautocompleter.css">autocompleter.css</a> from the plugin folder as an example to create your own stylessheets. You should use an absolute path (e.g. http://example.com/mystyles.css).', 'autocompleter'), AUTOCOMPLETER_URL) ?></p>
		</p>
		<p class="submit">
			<input class="button-primary" name="save" type="submit" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
</div>

<?php
}


/**
 * Add deprecation notice in WP Admin.
 */
function autocompleter_deprecation_notice() {
	// Only show notice for users who can actually uninstall or update plugins.
	if ( ! current_user_can( 'delete_plugins' ) && ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	global $wp_version;
	?>
	<div class="notice notice-warning">
		<p>
			<?php echo wp_kses(
				__( 'Kau-Boy\'s AutoCompleter <b>is deprecated and will be removed</b> from the plugin directory on <b>June 19, 2023</b>, 14 years after its first release. Thanks to everyone who used the plugin, gave constructive feedback, rated it and send me messages on how it helped them with their sites. This was <a href="https://kau-boys.de/19/allgemein/mein-erster-blog">my very first WordPress plugin, and it started my journey into the WordPress community</a>. If you see this message, you are one of few, who still have this plugin installed. But now feel free to remove it from your site. If you still want an auto-completion/suggestion, try out <a href="https://wordpress.org/plugins/wp-search-suggest/">WP Search Suggest</a>, which is still actively maintained. You can find my other plugins <a href="https://profiles.wordpress.org/kau-boy/#content-plugins">on my WordPress profile page</a>.', 'autocompleter' ),
				array( 'a' => array( 'href' => array() ), 'b' => array() )
			); ?>
		</p>
	</div>
	<?php
}

add_action('init', 'init_autocompleter');
add_action('admin_menu', 'autocompleter_admin_menu');
add_filter('plugin_action_links', 'autocompleter_filter_plugin_actions', 10, 2);
add_action( is_network_admin() ? 'network_admin_notices' : 'admin_notices', 'autocompleter_deprecation_notice' );
