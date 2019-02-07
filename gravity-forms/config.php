<?php
/**
 * Checks if the Gravity Forms plugin is activated
 *
 * If the Gravity Forms plugin is not active, then don't allow the
 * activation of this plugin.
 *
 */
function ic_gravity_modal_activate() {
  if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
    include_once( ABSPATH . '/wp-admin/includes/plugin.php' );
  }
  if ( current_user_can( 'activate_plugins' ) && ! class_exists( 'GFForms' ) ) {
    // Deactivate the plugin.
    deactivate_plugins( plugin_basename( __FILE__ ) );
    // Throw an error in the WordPress admin console.
    $error_message = '<p style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Oxygen-Sans,Ubuntu,Cantarell,\'Helvetica Neue\',sans-serif;font-size: 13px;line-height: 1.5;color:#444;">This plugin requires <a href="https://www.gravityforms.com/" target="_blank">Gravity Forms</a> plugin to be active.</p>';
    die( $error_message ); // WPCS: XSS ok.
  }
}
register_activation_hook( __FILE__, 'ic_gravity_modal_activate' );

/* Register Gravity Forms Addon */
function ic_gravity_modal_gform_loaded() {
  require_once( 'addon.php' );
  GFAddOn::register( 'ICModalAddOn' );
}
add_action( 'gform_loaded', 'ic_gravity_modal_gform_loaded');

function ic_gravity_modal_get_form(){
  $form_id = isset( $_GET['form_id'] ) ? absint( $_GET['form_id'] ) : 0;
	// Render an AJAX-enabled form.
  // https://www.gravityhelp.com/documentation/article/embedding-a-form/#function-call
  gravity_form( $form_id, false, false, false, $_GET, true );
	die();
}
add_action( 'wp_ajax_nopriv_ic_gravity_modal_get_form', 'ic_gravity_modal_get_form' );
add_action( 'wp_ajax_ic_gravity_modal_get_form', 'ic_gravity_modal_get_form' );

/* Scripts */
function ic_gravity_modal_load_scripts() {
  if (!is_admin()) {
    $allForms = GFAPI::get_forms();
    $forms = array();
    foreach($allForms as $form) {
      $formOpts = $form['ic-gravity-modal'];
      if ($formOpts['enabled'] === '1') {
        $forms[$form['id']] = $formOpts;
        gravity_form_enqueue_scripts(absint($form['id']), true);
      }
    }
    $json = sprintf("JSON.parse('%s')", json_encode($forms));
    $ajaxUrl = admin_url( 'admin-ajax.php' );
    $script = sprintf("IcGravityModalInit(%s, '%s');", $json, $ajaxUrl);
    /* Add scripts */
    wp_enqueue_script('vue', plugins_url( '../js/vue.js', __FILE__ ), array(), '', true);
    wp_enqueue_script('sweet-modal', plugins_url( '../js/sweet-modal.js', __FILE__ ), array('vue'), '', true);
    wp_enqueue_script('ic-gravity-modal', plugins_url( '../js/ic-gravity-modal.js', __FILE__ ), array('vue'), '', true);
    wp_add_inline_script('ic-gravity-modal', $script);
  }
}
add_action('wp_enqueue_scripts', 'ic_gravity_modal_load_scripts');