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

            Field::make('radio', 'select_api', __('Choose API'))
                ->set_options(array(
                    '1' => 'City Search',
                    '2' => 'Airport Search',
                    '3' => 'Air-Port-Codes',
                    '4' => 'API Ninjas | Airports'
                )),

            Field::make('checkbox', 'include_airports', __('Include Airports'))
                ->set_conditional_logic([
                    [
                        'field' => 'select_api',
                        'value' => '1'
                    ]
                ]),

            Field::make('complex', 'id_attributes', 'ID Attributes')
                ->set_layout('tabbed-horizontal')
                ->add_fields(array(
                    Field::make('text', 'id_strg', 'CSS ID')
                        ->set_attribute('placeholder', 'Enter the CSS ID of the field where you want to show the auto-completes.')
                ))

        ))

        ->add_fields(array(

            Field::make( 'html', 'amadeus_information_text' )
                ->set_html( '<h3>Amadeus Api Credentials:</h3>' ),
            Field::make('checkbox', 'amadeus_api_mode', __('Test mode')),

            Field::make('text', 'client_id', 'Client ID')
                ->set_attribute('placeholder', 'Enter Client ID here...'),
            Field::make('text', 'client_secret', 'Client Secret')
                ->set_attribute('placeholder', 'Enter Client Secret here...'),

            
            Field::make( 'html', 'ninja_information_text' )
                ->set_html( '<h3>Ninja Api-key:</h3>' ),
            Field::make('text', 'ninja_api_key', 'Ninja API Key')
                ->set_attribute('placeholder', 'Enter API Key here...'),
                
                
            Field::make( 'html', 'airportcode_information_text' )
                ->set_html( '<h3>Air-Port-Codes Api Credentials:</h3>' ),
            Field::make('text', 'apc_auth', 'APC Auth')
                ->set_attribute('placeholder', 'Enter APC Auth...'),
            Field::make('text', 'apc_auth_secret', 'APC Auth Secret')
                ->set_attribute('placeholder', 'Enter APC Auth Secret here...'),
            Field::make('checkbox', 'disable_cities', __('Disable City With Airport')),
            Field::make('checkbox', 'enable_cities', __('Enable Cities Separately')),

            Field::make('complex', 'id_attributes_for_cities', 'ID Attributes')
                ->set_conditional_logic([
                    [
                        'field' => 'enable_cities',
                        'value' => '1'
                    ]
                ])
                ->set_layout('tabbed-horizontal')
                ->add_fields(array(
                    Field::make('text', 'id_strg', 'CSS ID')
                        ->set_attribute('placeholder', 'Enter the CSS ID of the field where you want to show the auto-completes.')
                )),
                Field::make('checkbox', 'inl_country_codes', __('Include Country Codes'))
                ->set_conditional_logic([
                    [
                        'field' => 'enable_cities',
                        'value' => '1'
                    ]
                ])
        ));
}
