<?php

include AMADEUS_PLUGIN_PATH . 'includes/api-config.php';

$Is_airportCodes_Api = get_plugin_options_ap('select_api');
$get_ID_Attributes_in_autocomplete = getIdAttributes();

?>

<?php if (get_plugin_options_ap('amadeus_plugin_active') == 'yes') { ?>


<script>
var id_attr = '<?php echo $get_ID_Attributes_in_autocomplete ?>';


    var apiAuth = '<?php echo get_plugin_options_ap('apc_auth') ?>';
    var apiAuthSecret = '<?php echo get_plugin_options_ap('apc_auth_secret') ?>';
        if ($(id_attr).length) { // Initialize the apiKey variable
            const airportCodesUrl = "https://www.air-port-codes.com/api/v1" + '<?php echo $AIRPORT_CODES_API; ?>';
            let selectApi = '<?php echo $Is_airportCodes_Api; ?>';
            
            $(document).ready(function() {

                // Function to make an API request with the current access token
                function makeAirportCodeAPIRequest(request, response) {
                    var airportCodeKeyword = request.term;

                    var formData = new FormData();
                    formData.append('term', airportCodeKeyword); // Replace with your search term
                    formData.append('limit', '10'); // Replace with your limit
                    if (airportCodeKeyword.length >= 3) {
                        let apiUrl = '';
                        if (selectApi === '3') {
                            apiUrl = airportCodesUrl;
                        } else {
                            apiUrl = '';
                        }
                        $.ajax({
                            type: 'POST',
                            url: apiUrl,
                            data: formData,
                            headers: {
                                'APC-Auth': apiAuth,
                                'APC-Auth-Secret': apiAuthSecret
                            },
                            processData: false, // Prevent jQuery from processing the data
                            contentType: false, // Prevent jQuery from setting the content type

                            success: function (data) {
                                // console.log(data.airports);
                                var autocompleteData = data.airports.map(function(item) {
                                    if (!item.iata) {
                                        item.iata = 'Any Airport';
                                    }
                                    return {
                                        label: item.name + ', ' + item.iata + ', ' + item.city + ', ' + item.country.iso,
                                        value: item.name + ', ' + item.iata + ', ' + item.city + ', ' + item.country.iso
                                    };
                                });

                                response(autocompleteData);
                                
                            },
                            error: function (xhr, status, error) {
                                console.error('API Request Failed: ' + error);
                            }
                        });
                    }
                }

                // Autocomplete functionality
                $(id_attr).autocomplete({
                    source: makeAirportCodeAPIRequest,
                    minLength: 2,
                });
            });
        }
</script>



<?php } ?>