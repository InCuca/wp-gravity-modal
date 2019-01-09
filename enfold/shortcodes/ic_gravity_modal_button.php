<?php
/**
* Central Template builder class
*/

// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

class ic_sc_button extends avia_sc_button
{   
	/**
	 * Create the config array for the shortcode button
	 */
	function shortcode_insert_button()
	{
		parent::shortcode_insert_button();
		$this->config['name']		= __('Gravity Button', 'ic-gravity-modal' );
		$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-button.png";
		$this->config['order']		= 84;
		$this->config['shortcode'] 	= 'ic_gravity_modal_button';
		$this->config['tooltip'] 	= __('Creates a colored button to open an modal with a Gravity Form', 'ic-gravity-modal' );
	}

    /**
	 * Popup Elements
	 *
	 * If this function is defined in a child class the element automatically gets an edit button, that, when pressed
	 * opens a modal window that allows to edit the element properties
	 *
	 * @return void
	 */
	function popup_elements()
	{
		parent::popup_elements();
		$allForms = GFAPI::get_forms();
		$forms = array();
		foreach($allForms as $form) {
			$formOpts = $form['ic-gravity-modal'];
			if ($formOpts['enabled'] === '1') {
				$forms[$form['title']] = $form['id'];
			}
		}
		$this->elements[3] = array(	
			"name" 	=> __("Form", 'ic-gravity-modal' ),
			"desc" 	=> __("What form the modal shows?", 'avia_framework' ),
			"id" 	=> "form",
			"type" 	=> "select",
			"subtype" => $forms,
			"std" 	=> "",
		);
		unset($this->elements[4]);
	}

	/**
	 * Frontend Shortcode Handler
	 *
	 * @param array $atts array of attributes
	 * @param string $content text within enclosing form of shortcode element 
	 * @param string $shortcodename the shortcode found, when == callback name
	 * @return string $output returns the modified html string 
	 */
	function shortcode_handler($atts, $content = "", $shortcodename = "", $meta = "")
	{
		extract(AviaHelper::av_mobile_sizes($atts)); //return $av_font_classes, $av_title_font_classes and $av_display_classes 
		$atts =  shortcode_atts(array('label' => 'Click me', 
										'form' => '', 
										'color' => 'theme-color',
										'custom_bg' => '#444444',
										'custom_font' => '#ffffff',
										'size' => 'small',
										'position' => 'center',
										'icon_select' => 'yes',
										'icon' => '', 
										'font' =>'',
										'icon_hover' => '',
										'label_display'=>'',
										), $atts, $this->config['shortcode']);
										
		$display_char 	= av_icon($atts['icon'], $atts['font']);
		$extraClass 	= $atts['icon_hover'] ? "av-icon-on-hover" : "";
		
		if($atts['icon_select'] == "yes") $atts['icon_select'] = "yes-left-icon";
		
		$style = "";
		if($atts['color'] == "custom") 
		{
			$style .= "style='background-color:".$atts['custom_bg']."; border-color:".$atts['custom_bg']."; color:".$atts['custom_font']."; '";
		}
		
		
		$data = "";
		if(!empty($atts['label_display']) && $atts['label_display'] == "av-button-label-on-hover") 
		{
			$extraClass .= " av-button-label-on-hover ";
			$data = "data-avia-tooltip='".htmlspecialchars($atts['label'])."'";
			$atts['label'] = "";
		}
		
		if(empty($atts['label'])) $extraClass .= " av-button-notext ";
		
		
		$link  = '#gform-' . $atts['form'];
		$link  = ( ( $link == "http://" ) || ( $link == "manually" ) ) ? "" : $link;
		
		$content_html = "";
		if('yes-left-icon' == $atts['icon_select']) $content_html .= "<span class='avia_button_icon avia_button_icon_left ' {$display_char}></span>";
		$content_html .= "<span class='avia_iconbox_title' >".$atts['label']."</span>";
		if('yes-right-icon' == $atts['icon_select']) $content_html .= "<span class='avia_button_icon avia_button_icon_right' {$display_char}></span>";
		
		$output  = "";
		$output .= "<a href='{$link}' {$data} class='avia-button {$extraClass} {$av_display_classes} ".$this->class_by_arguments('icon_select, color, size, position' , $atts, true)."' {$style} >";
		$output .= $content_html;
		$output .= "</a>";
		
		$output =  "<div class='avia-button-wrap avia-button-".$atts['position']." ".$meta['el_class']."'>".$output."</div>";
		
		return $output;
	}
} // end class



