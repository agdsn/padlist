<?php
session_start();

// Unset all of the session variables
$_SESSION = array();

// Finally, destroy the session.
session_destroy();

// Redirect to keycloak logout
header('Location: https://alf.agdsn.de/realms/internal/protocol/openid-connect/logout');