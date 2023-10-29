<?php

use Carbon_Fields\Field;
use Carbon_Fields\Container;

add_action('after_setup_theme', 'load_carbon_fields_ap');
add_action('carbon_fields_register_fields', 'create_options_page_ap');



function load_carbon_fields_ap()
{
    \Carbon_Fields\Carbon_Fields::boot();
}

function create_options_page_ap()
{

    Container::make('theme_options', __('Amadeus'))
        ->set_page_parent('tools.php')
        ->set_icon('dashicons-carrot')
        ->set_page_menu_position(30)
        ->add_fields(array(

            Field::make('checkbox', 'amadeus_plugin_active', __('Active')),

        ))

        ->add_fields(array(

            Field::make('checkbox', 'include_airports', __('Include Airports')),

            Field::make('complex', 'id_attributes', 'ID Attributes')
                ->set_layout('tabbed-horizontal')
                ->add_fields(array(
                    Field::make('text', 'id_strg', 'CSS ID')
                        ->set_attribute('placeholder', 'Enter the CSS ID of the field where you want to show the auto-completes.')
                ))

        ))

        ->add_fields(array(

            Field::make('checkbox', 'amadeus_api_mode', __('Test mode')),

            Field::make('text', 'client_id', 'Client ID')
                ->set_attribute('placeholder', 'Enter Client ID here...'),
            Field::make('text', 'client_secret', 'Client Secret')
                ->set_attribute('placeholder', 'Enter Client Secret here...')

        ));
}
