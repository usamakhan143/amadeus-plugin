<?php

$LIVE_BASE_URL = "";
$TEST_BASE_URL = ""; // https://abc.com/amadeus/v1
$TOKEN_API = "/security/oauth2/token"; // Endpoint: /token
$AIRPORT_API = "/reference-data/locations?subType=AIRPORT&page%5Blimit%5D=10&page%5Boffset%5D=0&sort=analytics.travelers.score&view=FULL&";
$CITIES_API = "/reference-data/locations/cities?"; // Endpoint: /cities
$NINJAS_API = "https://api.api-ninjas.com/v1/airports?name=";
$LUNAJETS_API = "https://www.lunajets.com/api/getDestinations?";
$CLIENT_ID = carbon_get_theme_option('client_id'); // XXXXXXXXXXXXXXXXXXXXX
$CLIENT_SECRET = carbon_get_theme_option('client_secret'); // XXXXXXXXXXXXXXXXX