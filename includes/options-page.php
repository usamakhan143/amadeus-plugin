<?php

use Carbon_Fields\Field;
use Carbon_Fields\Container;

add_action('after_setup_theme', 'load_carbon_fields_ap');
add_action('carbon_fields_register_fields', 'create_options_page_ap');



function load_carbon_fields_ap()
{
    \Carbon_Fields\Carbon_Fields::boot();
}

function create_options_page_ap() {
    
    Container::make( 'theme_options', __( 'Amadeus' ) )
    ->set_page_parent('tools.php')
    ->set_icon( 'dashicons-carrot' )
    ->set_page_menu_position( 30 )
    ->add_fields( array(
        
        Field::make( 'checkbox', 'amadeus_plugin_active', __( 'Active' ) ),

        Field::make( 'complex', 'id_attributes' )
        ->set_layout( 'tabbed-horizontal' )
        ->add_fields( array(
            Field::make( 'text', 'id_strg', 'CSS ID' )
            ->set_attribute('placeholder', 'Enter the CSS ID of the field where you want to show the auto-completes.')
        ) )

    ));

}