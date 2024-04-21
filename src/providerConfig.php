<?php

require 'vendor/autoload.php';

use Stevenmaguire\OAuth2\Client\Provider\Keycloak;

$provider = new Keycloak([
    'authServerUrl'         => getenv('KEYCLOAK_AUTH_SERVER_URL'),
    'realm'                 => getenv('KEYCLOAK_REALM'),
    'clientId'              => getenv('KEYCLOAK_CLIENT_ID'),
    'clientSecret'          => getenv('KEYCLOAK_CLIENT_SECRET'),
    'redirectUri'           => getenv('KEYCLOAK_REDIRECT_URI'),
    'version'               => '24.0.3',
]);

return $provider;
