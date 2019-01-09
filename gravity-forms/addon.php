<?php
GFForms::include_addon_framework();
class ICModalAddOn extends GFAddOn {
    protected $_version = '1.0';
    protected $_min_gravityforms_version = '1.9';
    protected $_slug = 'ic-gravity-modal';
    protected $_path = 'ic-gravity-modal/gravity-forms/addon.php';
    protected $_full_path = __FILE__;
    protected $_title = 'InCuca Gravity Modal Add-On';
    protected $_short_title = 'Modal';

    /**
     * @var object|null $_instance If available, contains an instance of this class.
     */
    private static $_instance = null;
    
    /**
     * Returns an instance of this class, and stores it in the $_instance property.
     *
     * @return object $_instance An instance of this class.
     */
    public static function get_instance() {
        if ( self::$_instance == null ) {
            self::$_instance = new self();
        }
    
        return self::$_instance;
    }

    /**
     * Configures the settings which should be rendered on the Form Settings > Simple Add-On tab.
     *
     * @return array
     */
    public function form_settings_fields( $form ) {
        function getPercs() {
            $percs = array(
                array(
                    'label' => 'Auto',
                    'value' => null,
                )
            );
            for ($i = 0; $i <= 100; $i++) {
                if ($i % 10 === 0) {
                    array_push($percs, array(
                        'label' => $i . '%',
                        'value' => $i . '%',
                    ));
                }
            }
            return $percs;
        }

        return array(
            array(
                'title'  => 'Modal',
                'fields' => array(
                    array(
                        'label'   => esc_html__( 'Enable modal', 'ic-gravity-modal' ),
                        'type'    => 'checkbox',
                        'name'    => 'enabled',
                        'tooltip' => esc_html__( 'Enable modal for this form', 'ic-gravity-modal' ),
                        'choices' => array(
                            array(
                                'label' => esc_html__( 'Enabled', 'ic-gravity-modal' ),
                                'name'  => 'enabled',
                            ),
                        ),
                    ),
                    array(
                        'label'   => esc_html__( 'Modal width (%)', 'ic-gravity-modal' ),
                        'type'    => 'select',
                        'name'    => 'modalWidth',
                        'tooltip' => esc_html__( 'Modal width in % of screen', 'ic-gravity-modal' ),
                        'choices' => getPercs(),
                    ),
                    array(
                        'label'   => esc_html__( 'Modal Theme', 'ic-gravity-modal' ),
                        'type'    => 'select',
                        'name'    => 'modalTheme',
                        'tooltip' => esc_html__( 'Modal Theme', 'ic-gravity-modal' ),
                        'choices' => array(
                            array('label' => __('Light', 'ic-gravity-modal'), 'value' => 'light'),
                            array('label' => __('Dark', 'ic-gravity-modal'), 'value' => 'dark'),
                        ),
                    ),
                    array(
                        'label'   => esc_html__( 'Overlay Theme', 'ic-gravity-modal' ),
                        'type'    => 'select',
                        'name'    => 'overlayTheme',
                        'tooltip' => esc_html__( 'Overlay Theme', 'ic-gravity-modal' ),
                        'choices' => array(
                            array('label' => __('Light', 'ic-gravity-modal'), 'value' => 'light'),
                            array('label' => __('Dark', 'ic-gravity-modal'), 'value' => 'dark'),
                        ),
                    ),
                    array(
                        'label'   => esc_html__( 'Fullscreen on Mobile', 'ic-gravity-modal' ),
                        'type'    => 'checkbox',
                        'name'    => 'enableMobileFullscreen',
                        'tooltip' => esc_html__( 'If true, modal becomes fullscreen on mobile devices for better legibility.', 'ic-gravity-modal' ),
                        'choices' => array(
                            array(
                                'label' => esc_html__( 'Enabled', 'ic-gravity-modal' ),
                                'name'  => 'enableMobileFullscreen',
                            ),
                        ),
                    ),
                ),
            ),
        );
    }
}