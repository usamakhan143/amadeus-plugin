<?php

include AMADEUS_PLUGIN_PATH . 'includes/api-config.php';

?>

<script>
    var apiKey = ""; // Initialize the apiKey variable

    $(document).ready(function () {
    refreshToken();
    // Function to refresh the access token
    function refreshToken(callback) {
        console.log("Token Created Successfully");
        // Make a request to obtain a new access token
        $.ajax({
        url: '<?php echo $BASE_URL . $TOKEN_API ?>',
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        data: {
            grant_type: "client_credentials",
            client_id: '<?php echo $CLIENT_ID ?>',
            client_secret: '<?php echo $CLIENT_SECRET?>',
        },
        success: function (data) {
            // Handle the new access token
            var newAccessToken = data.access_token;

            // Set the new access token in the apiKey variable
            apiKey = newAccessToken;

            // Set a timer to refresh the token before it expires
            var newExpirationTime = data.expires_in;
            setTimeout(refreshToken, (newExpirationTime - 60) * 1000); // Renew 1 minute before expiration

            if (callback) {
            callback();
            }
        },
        error: function () {
            // Handle token refresh errors
        },
        });
    }

    // Function to make an API request with the current access token
    function makeAPIRequest(request, response) {
        var keyword = request.term;
        var maxResults = 10;

        if(keyword.length >= 3) {
            var apiUrl =
            '<?php echo $BASE_URL . $CITIES_API ?>' +
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
            success: function (data) {
                if (data.errors) {
                // Handle API errors
                console.log("Eoor", data.errors[0].title);
                return;
                }
                // Handle the API response and display results in the autocomplete
                var autocompleteData = data.data.map(function (item) {
                return {
                    label: item.name + ", " + item.address.countryCode, // Display city and country
                    value: item.name + ", " + item.address.countryCode, // Value to be placed in the input field
                };
                });

                // Display autocomplete suggestions
                response(autocompleteData);
            },
            error: function (e) {
                console.error("Error: ", e.responseJSON.errors[0]);
                refreshToken();          
            },
            });
        }
    }

    // Autocomplete functionality
    $("#autocomplete-input").autocomplete({
        source: makeAPIRequest,
        minLength: 2,
    });
    });
</script>