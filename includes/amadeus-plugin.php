<?php
add_action('wp_footer', 'Amadeus_Plugin_run');
add_action('wp_head', 'Run_Jquery');

function Amadeus_Plugin_run()
{
    if (get_plugin_options_ap('amadeus_plugin_active') == 'yes') {
        if(get_plugin_options_ap('select_api') == "2" || get_plugin_options_ap('select_api') == "1")
        {
            include AMADEUS_PLUGIN_PATH . 'includes/templates/amadeus-plugin.php';
        }
        else if(get_plugin_options_ap('select_api') == "3"){
            include AMADEUS_PLUGIN_PATH . 'includes/templates/luna-jets.php';
        }
        else {
            include AMADEUS_PLUGIN_PATH . 'includes/templates/ninjas.php';
        }
    }
}

function Run_Jquery()
{
?>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
    <script src="<?php echo AMADEUS_PLUGIN_URL . 'node_modules/jquery/dist/jquery.min.js'; ?>"> </script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php
}
