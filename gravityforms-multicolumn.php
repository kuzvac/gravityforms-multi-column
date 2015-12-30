<?php

/**
 * Plugin Name: GravityForms Multi Column
 * Description: WordPress GravityForms Multi Column Plugin
 * Author: WebLoft Designs Team
 * Author URI: http://webloftdesigns.com
 * Version: 1.0.0
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 */

add_filter( 'gform_add_field_buttons', 'add_column_field' );
function add_column_field( $field_groups ) {
	foreach ( $field_groups as &$group ) {
		if ( $group['name'] == 'standard_fields' ) {
			$group['fields'][] = array(
				'class'     => 'button',
				'data-type' => 'column_break',
				'value'     => __( 'New Column', 'gravityforms' ),
				'onclick'   => "StartAddField('column_break');"
			);
			break;
		}
	}

	return $field_groups;
}

add_filter( 'gform_field_type_title', 'add_column_assign_title', 10, 2 );
function add_column_assign_title( $title, $field_type ) {
	if ( $field_type == 'column_break' ) {
		return 'New Column';
	} else {
		return $title;
	}
}

add_action( 'gform_editor_js_set_default_values', 'add_column_set_defaults' );
function add_column_set_defaults(){
	?>
	//this hook is fired in the middle of a switch statement,
	//so we need to add a case for our new field type
	case "column_break" :
	field.label = "New Column"; //setting the default field label
	break;
	<?php
}

// Now we execute some javascript technicalitites for the field to load correctly
add_action( "gform_editor_js", "add_column_editor_js" );
function add_column_editor_js(){
	?>

	<script type='text/javascript'>

jQuery(document).ready(function($) {

// from forms.js; can add custom "column_break" as well
fieldSettings["column_break"] = ".label_setting, .css_class_setting"; //Display fields
});
</script>
	<?php
}

add_filter( 'gform_field_container', 'add_column_render', 10, 6 );
function add_column_render( $field_container, $field, $form, $css_class, $style, $field_content ) {
	// only modify column_break field
	if (IS_ADMIN || $field['type'] !== 'column_break') {
		return $field_container;
	}

	$ul_classes = GFCommon::get_ul_classes($form).' '.$field['cssClass'];
	return '</ul><ul class="'.$ul_classes.'">';
}