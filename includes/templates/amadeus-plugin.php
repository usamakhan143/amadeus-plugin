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

$BASE_URL = "";
(carbon_get_theme_option('amadeus_api_mode') != 'yes') ? $BASE_URL = $LIVE_BASE_URL : $BASE_URL = $TEST_BASE_URL;

?>

<script>
    var id_attr = '<?php echo $ids_separated ?>';
    if ($(id_attr).length) {
        var apiKey = ""; // Initialize the apiKey variable
        var amadeusBaseUrl = '<?php echo $BASE_URL; ?>';
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

                        console.log("Token Created Successfully");
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

                if (keyword.length >= 3) {
                    var apiUrl = amadeusBaseUrl + '<?php echo $CITIES_API ?>' +
                        "keyword=" +
                        keyword +
                        "&max=" +
                        maxResults;

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
                            var autocompleteData = data.data.map(function(item) {
                                return {
                                    label: item.name + ", " + item.address.countryCode, // Display city and country
                                    value: item.name + ", " + item.address.countryCode, // Value to be placed in the input field
                                };
                            });

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