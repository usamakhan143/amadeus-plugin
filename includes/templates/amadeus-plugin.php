<?php

include AMADEUS_PLUGIN_PATH . 'includes/api-config.php';
$id_attributes = get_plugin_options_ap('id_attributes');
if ($id_attributes) {
    $flattenedData = array();

    foreach ($id_attributes as $subArray) {
        foreach ($subArray as $value) {
            if ($value != '_') {
                $flattenedData[] = '#' . trim($value);
            }
        }
    }

    $resultString = implode(', ', $flattenedData);
}
$ids_separated = $resultString;

$select_api = get_plugin_options_ap('select_api');

$BASE_URL = "";
(get_plugin_options_ap('amadeus_api_mode') != 'yes') ? $BASE_URL = $LIVE_BASE_URL : $BASE_URL = $TEST_BASE_URL;

$include_airports = get_plugin_options_ap('include_airports');
$airport_query = '';
($include_airports == 'yes') ? $airport_query = '&include=AIRPORTS' : $airport_query = '';

?>

<?php if (get_plugin_options_ap('amadeus_plugin_active') == 'yes') { ?>
    <script>
        var id_attr = '<?php echo $ids_separated ?>';
        if ($(id_attr).length) {
            var apiKey = ""; // Initialize the apiKey variable
            var amadeusBaseUrl = '<?php echo $BASE_URL; ?>';
            const selectApi = '<?php echo $select_api; ?>';
            $(document).ready(function() {
                refreshToken();
                // Function to refresh the access token
                function refreshToken(callback) {
                    // Make a request to obtain a new access token
                    $.ajax({
                        url: amadeusBaseUrl + '<?php echo $TOKEN_API; ?>',
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        data: {
                            grant_type: "client_credentials",
                            client_id: '<?php echo $CLIENT_ID ?>',
                            client_secret: '<?php echo $CLIENT_SECRET ?>',
                        },
                        success: function(data) {
                            // Handle the new access token
                            var newAccessToken = data.access_token;

                            // Set the new access token in the apiKey variable
                            apiKey = newAccessToken;

                            // console.log("Token Created Successfully");
                            // Set a timer to refresh the token before it expires
                            var newExpirationTime = data.expires_in;
                            setTimeout(refreshToken, (newExpirationTime - 60) * 1000); // Renew 1 minute before expiration

                            if (callback) {
                                callback();
                            }
                        },
                        error: function() {
                            // Handle token refresh errors
                        },
                    });
                }

                // Function to make an API request with the current access token
                function makeAPIRequest(request, response) {
                    var keyword = request.term;
                    var maxResults = 10;
                    var includeAirport = '<?php echo $airport_query; ?>';

                    if (keyword.length >= 3) {
                        let apiUrl = '';
                        if (selectApi < 2) {
                            apiUrl = amadeusBaseUrl + '<?php echo $CITIES_API ?>' +
                                "keyword=" +
                                keyword +
                                "&max=" +
                                maxResults + includeAirport;
                        } else if (selectApi < 3) {
                            apiUrl = amadeusBaseUrl + '<?php echo $AIRPORT_API ?>' +
                                "keyword=" +
                                keyword;
                        } else {
                            apiUrl = '';
                        }

                        $.ajax({
                            url: apiUrl,
                            method: "GET",
                            headers: {
                                accept: "application/vnd.amadeus+json",
                                Authorization: "Bearer " + apiKey, // Use the current access token
                            },
                            success: function(data) {
                                if (data.errors) {
                                    // Handle API errors
                                    console.log("Eoor", data.errors[0].title);
                                    return;
                                }
                                // Handle the API response and display results in the autocomplete
                                // console.log(data.data);
                                if (includeAirport === '') {
                                    var autocompleteData = data.data.map(function(item) {
                                        return {
                                            label: item.name + ", " + item.address.countryCode, // Display city and country
                                            value: item.name + ", " + item.address.countryCode, // Value to be placed in the input field
                                        };
                                    });
                                } else {
                                    var autocompleteData = [];

                                    if (data.data && Array.isArray(data.data)) {
                                        autocompleteData = data.data.map(function(item) {
                                            if (item.relationships && Array.isArray(item.relationships)) {
                                                airportsInCity = item.relationships.map(function(airport) {
                                                    return airport.id;
                                                }).join(', ');

                                                return {
                                                    label: item.name + ', ' + item.address.countryCode + ', Airports: ' + airportsInCity,
                                                    value: item.name + ', ' + item.address.countryCode + ', Airports: ' + airportsInCity
                                                };
                                            }
                                            return {
                                                label: 'N/A',
                                                value: 'N/A'
                                            }; // or other suitable default values
                                        });
                                    }
                                }



                                // Display autocomplete suggestions
                                response(autocompleteData);
                            },
                            error: function(e) {
                                console.error("Error: ", e.responseJSON.errors[0]);
                                refreshToken();
                            },
                        });
                    }
                }

                // Autocomplete functionality
                $(id_attr).autocomplete({
                    source: makeAPIRequest,
                    minLength: 2,
                });
            });
        }
    </script>
<?php } ?>