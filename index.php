<?php
/*
Plugin Name: VC MailChimp
Plugin URI: https://wpclever.net/
Description: Ultimate MailChimp Form Builder for WPBakery Page Builder (formerly Visual Composer).
Version: 2.1.1
Author: WPclever.net
Author URI: https://wpclever.net
Text Domain: vcmc
Domain Path: /languages/
*/

define( 'VCMC_VERSION', '2.1.1' );
define( 'VCMC_URI', plugin_dir_url( __FILE__ ) );
define( 'VCMC_PATH', plugin_dir_path( __FILE__ ) );
define( 'VCMC_REVIEWS', 'https://wordpress.org/support/plugin/vc-mailchimp/reviews/?filter=5' );
define( 'VCMC_CHANGELOGS', 'https://wordpress.org/plugins/vc-mailchimp/#developers' );
define( 'VCMC_DISCUSSION', 'https://wordpress.org/support/plugin/vc-mailchimp' );
if ( ! defined( 'WPC_URI' ) ) {
	define( 'WPC_URI', VCMC_URI );
}

include( 'includes/wpc-menu.php' );
include( 'includes/wpc-dashboard.php' );
include( 'class/helper.php' );

add_action( 'init', 'vcmc_init' );
add_action( 'admin_menu', 'vcmc_admin_menu' );
add_action( 'plugins_loaded', 'vcmc_load_textdomain' );
add_filter( 'plugin_action_links', 'vcmc_action_links', 10, 2 );
add_action( 'wp_enqueue_scripts', 'vcmc_enqueue_script' );
add_action( 'admin_enqueue_scripts', 'vcmc_admin_enqueue_scripts' );
add_action( 'wp_ajax_vcmc_subscribe', 'vcmc_subscribe_process' );
add_action( 'wp_ajax_nopriv_vcmc_subscribe', 'vcmc_subscribe_process' );
add_action( 'wp_ajax_vcmc_get_lists', 'vcmc_get_lists_process' );
add_action( 'wp_ajax_nopriv_vcmc_get_lists', 'vcmc_get_lists_process' );

function vcmc_load_textdomain() {
	load_plugin_textdomain( 'vcmc', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}

function vcmc_admin_menu() {
	add_submenu_page( 'wpclever', esc_html__( 'VC MailChimp', 'vcmc' ), esc_html__( 'VC MailChimp', 'vcmc' ), 'manage_options', 'wpclever-vcmc', 'vcmc_admin_menu_content' );
}

function vcmc_admin_menu_content() {
	$page_slug  = 'wpclever-vcmc';
	$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'how';
	?>
	<div class="wpclever_settings_page wrap">
		<h1 class="wpclever_settings_page_title"><?php echo esc_html__( 'VC MailChimp', 'vcmc' ) . ' ' . VCMC_VERSION; ?></h1>
		<div class="wpclever_settings_page_desc about-text">
			<p>
				<?php printf( esc_html__( 'Thank you for using our plugin! If you are satisfied, please reward it a full five-star %s rating.', 'vcmc' ), '<span style="color:#ffb900">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ); ?>
				<br/>
				<a href="<?php echo esc_url( VCMC_REVIEWS ); ?>"
				   target="_blank"><?php esc_html_e( 'Reviews', 'vcmc' ); ?></a> | <a
					href="<?php echo esc_url( VCMC_CHANGELOGS ); ?>"
					target="_blank"><?php esc_html_e( 'Changelogs', 'vcmc' ); ?></a>
				| <a href="<?php echo esc_url( VCMC_DISCUSSION ); ?>"
				     target="_blank"><?php esc_html_e( 'Discussion', 'vcmc' ); ?></a>
			</p>
		</div>
		<div class="wpclever_settings_page_nav">
			<h2 class="nav-tab-wrapper">
				<a href="?page=<?php echo $page_slug; ?>&amp;tab=how"
				   class="nav-tab <?php echo $active_tab == 'how' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'How to use?', 'vcmc' ); ?></a>
				<a href="?page=<?php echo $page_slug; ?>&amp;tab=premium"
				   class="nav-tab <?php echo $active_tab == 'premium' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Premium Version', 'vcmc' ); ?></a>
			</h2>
		</div>
		<div class="wpclever_settings_page_content">
			<?php if ( $active_tab == 'how' ) { ?>
				<p>
					<?php esc_html_e( 'Please follow these steps to add MailChimp to a page (screenshots will be opened in the new tab)', 'vcmc' ); ?>
				</p>
				<ol>
					<li><?php esc_html_e( 'Add "MailChimp Form" element first', 'vcmc' ); ?> <a
							href="http://prntscr.com/ixtxvf" target="_blank">http://prntscr.com/ixtxvf</a></li>
					<li><?php esc_html_e( 'Fill the API key then press "Get lists"', 'vcmc' ); ?> <a
							href="http://prntscr.com/ixtzc8" target="_blank">http://prntscr.com/ixtzc8</a></li>
					<li><?php esc_html_e( 'Choose the list you want', 'vcmc' ); ?> <a href="http://prntscr.com/ixtzs0"
					                                                                  target="_blank">http://prntscr.com/ixtzs0</a>
					</li>
					<li><?php esc_html_e( 'Choose the style and customize messages', 'vcmc' ); ?> <a
							href="http://prntscr.com/ixu059" target="_blank">http://prntscr.com/ixu059</a></li>
					<li><?php esc_html_e( 'Add other elements to this form (eg. MailChimp Name, MailChimp Email, MailChimp Field)', 'vcmc' ); ?>
						<a
							href="http://prntscr.com/ixu0ld" target="_blank">http://prntscr.com/ixu0ld</a></li>
					<li><?php esc_html_e( 'Add "MailChimp Submit" element to finish the form', 'vcmc' ); ?> <a
							href="http://prntscr.com/ixu137" target="_blank">http://prntscr.com/ixu137</a></li>
				</ol>
			<?php } elseif ( $active_tab == 'premium' ) { ?>
				Get the Premium Version just $15! <a
					href="https://wpclever.net/downloads/vc-mailchimp" target="_blank">https://wpclever.net/downloads/vc-mailchimp</a>
				<br/>
				<br/>
				<strong>Extra features for Premium Version</strong>
				<ul>
					<li>- Add custom fields with many types</li>
					<li>- Get the lifetime update & premium support</li>
				</ul>
			<?php } ?>
		</div>
	</div>
	<?php
}

function vcmc_action_links( $links, $file ) {
	static $plugin;
	if ( ! isset( $plugin ) ) {
		$plugin = plugin_basename( __FILE__ );
	}
	if ( $plugin == $file ) {
		$how_link = '<a href="' . admin_url( 'admin.php?page=wpclever-vcmc&tab=how' ) . '">' . esc_html__( 'How to use?', 'vcmc' ) . '</a>';
		$links[]  = '<a href="' . admin_url( 'admin.php?page=wpclever-vcmc&tab=premium' ) . '">' . esc_html__( 'Premium Version', 'vcmc' ) . '</a>';
		array_unshift( $links, $how_link );
	}

	return $links;
}

function vcmc_enqueue_script() {
	wp_enqueue_style( 'growl', VCMC_URI . 'libs/jquery.growl/stylesheets/jquery.growl.css' );
	wp_enqueue_script( 'growl', VCMC_URI . 'libs/jquery.growl/javascripts/jquery.growl.js', array( 'jquery' ), VCMC_VERSION, true );
	wp_enqueue_style( 'vcmc-frontend', VCMC_URI . 'assets/css/frontend.css' );
	wp_enqueue_script( 'vcmc-frontend', VCMC_URI . 'assets/js/frontend.js', array( 'jquery' ), VCMC_VERSION, true );
	wp_localize_script( 'vcmc-frontend', 'vcmc_vars', array(
		'vcmc_ajax_url' => admin_url( 'admin-ajax.php' ),
		'vcmc_nonce'    => wp_create_nonce( 'vcmc_nonce' )
	) );
}

function vcmc_admin_enqueue_scripts() {
	wp_enqueue_style( 'vcmc-frontend', VCMC_URI . 'assets/css/backend.css' );
	wp_enqueue_script( 'vcmc-backend', VCMC_URI . 'assets/js/backend.js', array( 'jquery' ), VCMC_VERSION, true );
	wp_localize_script( 'vcmc-backend', 'vcmc_vars', array(
		'vcmc_ajax_url' => admin_url( 'admin-ajax.php' ),
		'vcmc_nonce'    => wp_create_nonce( 'vcmc_nonce' )
	) );
}

function vcmc_init() {
	if ( class_exists( 'WPBakeryShortCodesContainer' ) && class_exists( 'WPBakeryShortCode' ) ) {
		require_once( VCMC_PATH . 'params/vcmc_api.php' );
		require_once( VCMC_PATH . 'params/vcmc_select_image.php' );
		add_shortcode( 'vc_mailchimp_form', 'vcmc_form_do_shortcode' );
		add_shortcode( 'vc_mailchimp_name', 'vcmc_name_do_shortcode' );
		add_shortcode( 'vc_mailchimp_email', 'vcmc_email_do_shortcode' );
		add_shortcode( 'vc_mailchimp_field', 'vcmc_field_do_shortcode' );
		add_shortcode( 'vc_mailchimp_submit', 'vcmc_submit_do_shortcode' );

		class WPBakeryShortCode_VC_MailChimp_Form extends WPBakeryShortCodesContainer {
		}

		class WPBakeryShortCode_VC_MailChimp_Name extends WPBakeryShortCode {
		}

		class WPBakeryShortCode_VC_MailChimp_Email extends WPBakeryShortCode {
		}

		class WPBakeryShortCode_VC_MailChimp_Field extends WPBakeryShortCode {
		}

		class WPBakeryShortCode_VC_MailChimp_Submit extends WPBakeryShortCode {
		}

		vc_map(
			array(
				'name'                    => esc_html__( 'VC MailChimp Form', 'vcmc' ),
				'category'                => esc_html__( 'VC MailChimp', 'vcmc' ),
				'icon'                    => 'vcmc-icon vcmc-form',
				'base'                    => 'vc_mailchimp_form',
				'content_element'         => true,
				'show_settings_on_create' => true,
				'is_container'            => true,
				'js_view'                 => 'VcColumnView',
				'as_parent'               => array( 'except' => 'vc_mailchimp_form' ),
				'params'                  => array(
					array(
						'type'        => 'vcmc_api',
						'heading'     => esc_html__( 'MailChimp API key', 'vcmc' ),
						'description' => esc_html__( 'Please fill the MailChimp API key then press Get lists and choose one list.', 'vcmc' ),
						'param_name'  => 'api_key',
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Skip confirm', 'vcmc' ),
						'description' => esc_html__( 'If enable, user do not need to confirm via email when subscribed.', 'vcmc' ),
						'param_name'  => 'skip_confirm',
					),
					array(
						'type'        => 'vcmc_select_image',
						'heading'     => esc_html__( 'Style', 'vcmc' ),
						'description' => esc_html__( 'Choose a preset color for the form elements.', 'vcmc' ),
						'param_name'  => 'style',
						'std'         => '01',
						'value'       => array(
							'01'     => array(
								'name'  => esc_html__( 'Style 01', 'vcmc' ),
								'image' => VCMC_URI . 'assets/images/style-01.jpg',
							),
							'02'     => array(
								'name'  => esc_html__( 'Style 02', 'vcmc' ),
								'image' => VCMC_URI . 'assets/images/style-02.jpg',
							),
							'03'     => array(
								'name'  => esc_html__( 'Style 03', 'vcmc' ),
								'image' => VCMC_URI . 'assets/images/style-03.jpg',
							),
							'04'     => array(
								'name'  => esc_html__( 'Style 04', 'vcmc' ),
								'image' => VCMC_URI . 'assets/images/style-04.jpg',
							),
							'05'     => array(
								'name'  => esc_html__( 'Style 05', 'vcmc' ),
								'image' => VCMC_URI . 'assets/images/style-05.jpg',
							),
							'06'     => array(
								'name'  => esc_html__( 'Style 06', 'vcmc' ),
								'image' => VCMC_URI . 'assets/images/style-06.jpg',
							),
							'07'     => array(
								'name'  => esc_html__( 'Style 07', 'vcmc' ),
								'image' => VCMC_URI . 'assets/images/style-07.jpg',
							),
							'08'     => array(
								'name'  => esc_html__( 'Style 08', 'vcmc' ),
								'image' => VCMC_URI . 'assets/images/style-08.jpg',
							),
							'09'     => array(
								'name'  => esc_html__( 'Style 09', 'vcmc' ),
								'image' => VCMC_URI . 'assets/images/style-09.jpg',
							),
							'10'     => array(
								'name'  => esc_html__( 'Style 10', 'vcmc' ),
								'image' => VCMC_URI . 'assets/images/style-10.jpg',
							),
							'custom' => array(
								'name'  => esc_html__( 'Custom', 'vcmc' ),
								'image' => '',
							),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'vcmc' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'Style particular content element differently.', 'vcmc' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Position', 'vcmc' ),
						'group'       => esc_html__( 'Messages', 'vcmc' ),
						'param_name'  => 'messages_position',
						'std'         => 'br',
						'value'       => array(
							esc_html__( 'Top Left', 'vcmc' )     => 'tl',
							esc_html__( 'Top Right', 'vcmc' )    => 'tr',
							esc_html__( 'Bottom Left', 'vcmc' )  => 'bl',
							esc_html__( 'Bottom Right', 'vcmc' ) => 'br',
						),
						'save_always' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Successfully subscribed', 'vcmc' ),
						'param_name'  => 'messages_successfully',
						'value'       => 'Thank you, your sign-up request was successful! Please check your email inbox to confirm.',
						'group'       => esc_html__( 'Messages', 'vcmc' ),
						'save_always' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Successfully subscribed (Skip confirm)', 'vcmc' ),
						'param_name'  => 'messages_successfully_skip',
						'value'       => 'Thank you, your sign-up request was successful!',
						'group'       => esc_html__( 'Messages', 'vcmc' ),
						'save_always' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Already subscribed', 'vcmc' ),
						'param_name'  => 'messages_already',
						'value'       => 'Given email address is already subscribed, thank you!',
						'group'       => esc_html__( 'Messages', 'vcmc' ),
						'save_always' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Email field missing', 'vcmc' ),
						'param_name'  => 'messages_missing_email',
						'value'       => 'Please fill in your email.',
						'group'       => esc_html__( 'Messages', 'vcmc' ),
						'save_always' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Invalid email address', 'vcmc' ),
						'param_name'  => 'messages_invalid_email',
						'value'       => 'Please provide a valid email address.',
						'group'       => esc_html__( 'Messages', 'vcmc' ),
						'save_always' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Required field missing', 'vcmc' ),
						'param_name'  => 'messages_missing',
						'value'       => 'Please fill in the required fields.',
						'group'       => esc_html__( 'Messages', 'vcmc' ),
						'save_always' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'General error', 'vcmc' ),
						'param_name'  => 'messages_error',
						'value'       => 'Oops. Something went wrong. Please try again later.',
						'group'       => esc_html__( 'Messages', 'vcmc' ),
						'save_always' => true,
					),
					array(
						'type'       => 'css_editor',
						'heading'    => esc_html__( 'CSS', 'vcmc' ),
						'param_name' => 'css',
						'group'      => esc_html__( 'Design Options', 'vcmc' ),
					),
				)
			)
		);

		vc_map(
			array(
				'name'            => esc_html__( 'VC MailChimp Name', 'vcmc' ),
				'category'        => esc_html__( 'VC MailChimp', 'vcmc' ),
				'icon'            => 'vcmc-icon vcmc-name',
				'base'            => 'vc_mailchimp_name',
				'content_element' => true,
				'params'          => array(
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Type', 'vcmc' ),
						'description' => esc_html__( 'Choose first name or last name for this field.', 'vcmc' ),
						'param_name'  => 'type',
						'std'         => 'lname',
						'value'       => array(
							esc_html__( 'Last name', 'vcmc' )  => 'lname',
							esc_html__( 'First name', 'vcmc' ) => 'fname',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Label', 'vcmc' ),
						'param_name'  => 'label',
						'admin_label' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Place holder', 'vcmc' ),
						'param_name'  => 'place_holder',
						'admin_label' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Initial value', 'vcmc' ),
						'param_name'  => 'initial_value',
						'admin_label' => true,
					),
				)
			)
		);

		vc_map(
			array(
				'name'            => esc_html__( 'VC MailChimp Email', 'vcmc' ),
				'category'        => esc_html__( 'VC MailChimp', 'vcmc' ),
				'icon'            => 'vcmc-icon vcmc-email',
				'base'            => 'vc_mailchimp_email',
				'content_element' => true,
				'params'          => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Label', 'vcmc' ),
						'param_name'  => 'label',
						'admin_label' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Place holder', 'vcmc' ),
						'param_name'  => 'place_holder',
						'admin_label' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Initial value', 'vcmc' ),
						'param_name'  => 'initial_value',
						'admin_label' => true,
					),
				)
			)
		);

		vc_map(
			array(
				'name'            => esc_html__( 'VC MailChimp Field', 'vcmc' ),
				'category'        => esc_html__( 'VC MailChimp', 'vcmc' ),
				'icon'            => 'vcmc-icon vcmc-field',
				'base'            => 'vc_mailchimp_field',
				'content_element' => true,
				'params'          => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Field tag', 'vcmc' ),
						'description' => esc_html__( 'This value must same with the tag in MailChimp config ( http://prntscr.com/ef01qr )', 'vcmc' ),
						'param_name'  => 'field_tag',
						'admin_label' => true,
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Type', 'vcmc' ),
						'param_name' => 'type',
						'std'        => 'text',
						'value'      => array(
							esc_html__( 'Text', 'vcmc' )     => 'text',
							esc_html__( 'Number', 'vcmc' )   => 'number',
							esc_html__( 'Email', 'vcmc' )    => 'email',
							esc_html__( 'Tel', 'vcmc' )      => 'tel',
							esc_html__( 'Checkbox', 'vcmc' ) => 'checkbox',
							esc_html__( 'Dropdown', 'vcmc' ) => 'dropdown',
							esc_html__( 'Radio', 'vcmc' )    => 'radio',
							esc_html__( 'Password', 'vcmc' ) => 'password',
							esc_html__( 'Hidden', 'vcmc' )   => 'hidden',
						),
					),
					array(
						'type'        => 'param_group',
						'heading'     => esc_html__( 'Values', 'vcmc' ),
						'description' => esc_html__( 'Add value for checkbox or dropdown field.', 'vcmc' ),
						'param_name'  => 'values',
						'dependency'  => array(
							'element' => 'type',
							'value'   => array( 'checkbox', 'dropdown', 'radio' )
						),
						'params'      => array(
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Value', 'vcmc' ),
								'param_name'  => 'value',
								'admin_label' => true,
							),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Label', 'vcmc' ),
						'param_name'  => 'label',
						'admin_label' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Place holder', 'vcmc' ),
						'param_name'  => 'place_holder',
						'admin_label' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Initial value', 'vcmc' ),
						'param_name'  => 'initial_value',
						'admin_label' => true,
					),
				)
			)
		);

		vc_map(
			array(
				'name'            => esc_html__( 'VC MailChimp Submit', 'vcmc' ),
				'category'        => esc_html__( 'VC MailChimp', 'vcmc' ),
				'icon'            => 'vcmc-icon vcmc-submit',
				'base'            => 'vc_mailchimp_submit',
				'content_element' => true,
				'params'          => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Button text', 'vcmc' ),
						'param_name'  => 'button_text',
						'value'       => esc_html__( 'Subscribe', 'vcmc' ),
						'admin_label' => true,
					),
				)
			)
		);
	}
}

function vcmc_subscribe_process() {
	if ( ! isset( $_POST['vcmc_nonce'] ) || ! wp_verify_nonce( $_POST['vcmc_nonce'], 'vcmc_nonce' ) ) {
		die( esc_html__( 'Permissions check failed', 'vcmc' ) );
	}
	$vcmc_api                           = esc_attr( $_POST['vcmc_api'] );
	$vcmc_list                          = esc_attr( $_POST['vcmc_list'] );
	$vcmc_list                          = str_replace( 'vcmc_', '', $vcmc_list );
	$vcmc_skip                          = esc_attr( $_POST['vcmc_skip'] );
	$vcmc_fname                         = esc_html( $_POST['vcmc_fname'] );
	$vcmc_lname                         = esc_html( $_POST['vcmc_lname'] );
	$vcmc_email                         = esc_html( $_POST['vcmc_email'] );
	$vcmc_fields                        = vcmc_sanitize( $_POST['vcmc_fields'] );
	$vcmc_fields                        = str_replace( "\\", "", $vcmc_fields );
	$vcmc_fields_obj                    = json_decode( $vcmc_fields );
	$vcmc_data                          = array();
	$vcmc_data['email_address']         = $vcmc_email;
	$vcmc_data['merge_fields']['FNAME'] = $vcmc_fname;
	$vcmc_data['merge_fields']['LNAME'] = $vcmc_lname;
	if ( count( (array) $vcmc_fields_obj ) > 0 ) {
		foreach ( $vcmc_fields_obj as $key => $value ) {
			$vcmc_data['merge_fields'][ $key ] = $value;
		}
	}
	if ( $vcmc_api_key = get_option( $vcmc_api ) ) {
		// if has api key
		if ( $vcmc_list != '' ) {
			// if has a list
			if ( $vcmc_skip == 'true' ) {
				// skip verify
				$vcmc_data['status'] = 'subscribed';
			} else {
				// pending, verify via email
				$vcmc_data['status'] = 'pending';
			}
			$vcmc_subscribe = VCMC_Helper::subscribe( $vcmc_data, $vcmc_api_key, $vcmc_list );
			echo $vcmc_subscribe;
		}
	}
	die();
}

function vcmc_get_lists_process() {
	if ( ! isset( $_POST['vcmc_nonce'] ) || ! wp_verify_nonce( $_POST['vcmc_nonce'], 'vcmc_nonce' ) ) {
		die( esc_html__( 'Permissions check failed', 'vcmc' ) );
	}
	$api_key    = esc_attr( $_POST['api_key'] );
	$vcmc_lists = VCMC_Helper::get_lists( $api_key );
	echo $vcmc_lists;
	die();
}

function vcmc_form_do_shortcode( $atts, $content ) {
	extract( shortcode_atts( array(
		'api_key'                    => '',
		'skip_confirm'               => false,
		'style'                      => '01',
		'el_class'                   => '',
		'messages_position'          => 'br',
		'messages_successfully'      => '',
		'messages_successfully_skip' => '',
		'messages_already'           => '',
		'messages_invalid_email'     => '',
		'messages_missing'           => '',
		'messages_missing_email'     => '',
		'messages_error'             => '',
		'css'                        => '',
	), $atts ) );
	$message      = array(
		'messages_position'          => $messages_position,
		'messages_successfully'      => $messages_successfully,
		'messages_successfully_skip' => $messages_successfully_skip,
		'messages_already'           => $messages_already,
		'messages_invalid_email'     => $messages_invalid_email,
		'messages_missing'           => $messages_missing,
		'messages_missing_email'     => $messages_missing_email,
		'messages_error'             => $messages_error,
	);
	$message_json = json_encode( $message );
	$api_key_arr  = explode( ',', $api_key );
	$css_class    = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'vc_mailchimp_form', $atts );
	$key_option   = VCMC_Helper::generate_key( 'vcmc_', $api_key_arr[0] );
	if ( $key_option != '' ) {
		update_option( $key_option, $api_key_arr[0] );
	}
	$output = '<form class="vcmc-form ' . esc_attr( $el_class . ' style-' . $style . ' ' . $css_class ) . '" data-api="' . esc_attr( $key_option ) . '" data-list="' . esc_attr( ( isset( $api_key_arr[1] ) && ( $api_key_arr[1] != '' ) ) ? 'vcmc_' . $api_key_arr[1] : '' ) . '" data-skip="' . ( $skip_confirm ? 'true' : 'false' ) . '" data-message="' . htmlspecialchars( $message_json, ENT_QUOTES, 'UTF-8' ) . '" action="">';
	$output .= do_shortcode( $content );
	$output .= '</form>';

	return $output;
}

function vcmc_name_do_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'type'          => 'lname',
		'label'         => '',
		'place_holder'  => '',
		'initial_value' => '',
	), $atts ) );
	$output = '';
	$uid    = uniqid( 'vcmc-name-' );
	if ( $label != '' ) {
		$output .= '<label class="vcmc-label vcmc-name-label" for="' . esc_attr( $uid ) . '">' . esc_html( $label ) . '</label>';
	}
	$output .= '<input type="text" id="' . esc_attr( $uid ) . '" class="vcmc-name vcmc-' . ( $type == 'lname' ? 'lname' : 'fname' ) . '" value="' . esc_html( $initial_value ) . '" placeholder="' . esc_html( $place_holder ) . '"/>';

	return $output;
}

function vcmc_email_do_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'label'         => '',
		'place_holder'  => '',
		'initial_value' => '',
	), $atts ) );
	$output = '';
	$uid    = uniqid( 'vcmc-email-' );
	if ( $label != '' ) {
		$output .= '<label class="vcmc-label vcmc-email-label" for="' . esc_attr( $uid ) . '">' . esc_html( $label ) . '</label>';
	}
	$output .= '<input type="email" id="' . esc_attr( $uid ) . '" class="vcmc-email" value="' . esc_html( $initial_value ) . '" placeholder="' . esc_html( $place_holder ) . '"/>';

	return $output;
}

function vcmc_field_do_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'field_tag'     => '',
		'type'          => 'text',
		'values'        => '',
		'label'         => '',
		'place_holder'  => '',
		'initial_value' => '',
	), $atts ) );


	return '* The custom field just available on the Premium Version, click <a href="https://wpclever.net/downloads/vc-mailchimp">here</a> to buy!';
}

function vcmc_submit_do_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'button_text' => esc_html__( 'Subscribe', 'vcmc' ),
	), $atts ) );
	$output = '<button class="vcmc-submit" type="submit">' . esc_html( $button_text ) . '</button><span class="vcmc-message"></span>';

	return $output;
}

function vcmc_sanitize( $str, $quotes = ENT_NOQUOTES ) {
	$str = htmlspecialchars( $str, $quotes );

	return $str;
}