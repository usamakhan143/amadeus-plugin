<?php

include AMADEUS_PLUGIN_PATH . 'includes/api-config.php';

$Is_ninjas_Api = get_plugin_options_ap('select_api');
$get_ID_Attributes = getIdAttributes();

?>

<?php if (get_plugin_options_ap('amadeus_plugin_active') == 'yes') { ?>

    <script>
        var id_attr = '<?php echo $get_ID_Attributes ?>';
        if ($(id_attr).length) {
            var apiKey = '<?php echo get_plugin_options_ap('ninja_api_key') ?>'; // Initialize the apiKey variable
            const ninjasUrl = "https://api.api-ninjas.com/v1" + '<?php echo $NINJAS_API; ?>';
            let selectApi = '<?php echo $Is_ninjas_Api; ?>';
            
            $(document).ready(function() {

                // Function to make an API request with the current access token
                function makeNinjaAPIRequest(request, response) {
                    var ninjaKeyword = request.term;

                    if (ninjaKeyword.length >= 3) {
                        let apiUrl = '';
                        if (selectApi === '4') {
                            apiUrl = ninjasUrl + ninjaKeyword;
                        } else {
                            apiUrl = '';
                        }
                        $.ajax({
                            url: apiUrl,
                            method: "GET",
                            headers: {
                                'X-Api-Key': apiKey, // Use the current access token
                            },
                            contentType: 'application/json',
                            success: function(data) {
                                
                                // Handle the API response and display results in the autocomplete
                                // console.log(data);
                                let autocompleteData = data.map(function(item) {
                                    
                                    if (!item.iata) {
                                        item.iata = "Any Airport";
                                    }
                                    return {
                                        label: item.name + ", " + item.icao + ", " + item.iata + ", " + item.city + ", " + item.country, // Display city and country
                                        value: item.name + ", " + item.icao + ", " + item.iata + ", " + item.city + ", " + item.country // Value to be placed in the input field
                                    };
                                });



                                // Display autocomplete suggestions
                                response(autocompleteData);
                            },
                            error: function(e) {
                                console.error("Error: ", e);
                            },
                        });
                    }
                }

                // Autocomplete functionality
                $(id_attr).autocomplete({
                    source: makeNinjaAPIRequest,
                    minLength: 2,
                });
            });
        }
    </script>


<?php } ?>