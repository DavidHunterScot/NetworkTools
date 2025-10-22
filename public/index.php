<?php

session_start();

$requested_endpoint = isset( $_SERVER[ 'REQUEST_URI' ] ) && $_SERVER[ 'REQUEST_URI' ] ? $_SERVER[ 'REQUEST_URI' ] : '/';

$requested_endpoint = trim( $requested_endpoint );

while( substr( $requested_endpoint, 0, 1 ) == '/' )
{
    $requested_endpoint = substr( $requested_endpoint, 1 );
}

while( substr( $requested_endpoint, -1 ) == '/' )
{
    $requested_endpoint = substr( $requested_endpoint, 0, -1 );
}

$requested_endpoint = trim( $requested_endpoint );

$requested_endpoint_parts = explode( '/', $requested_endpoint );

$default_controller = 'DefaultController';
$requested_controller = $default_controller;

$default_method = 'index';
$requested_method = $default_method;

if( count( $requested_endpoint_parts ) >= 1 && $requested_endpoint_parts[ 0 ] && is_file( '../private/controllers/' . ucfirst( $requested_endpoint_parts[ 0 ] ) . 'Controller.php' ) )
{
    $requested_controller = ucfirst( $requested_endpoint_parts[ 0 ] ) . 'Controller';
    unset( $requested_endpoint_parts[ 0 ] );
    $requested_endpoint_parts = array_values( $requested_endpoint_parts );
}

include_once '../private/controllers/' . $default_controller . '.php';
include_once '../private/controllers/' . $requested_controller . '.php';

$default_controller_instance = new $default_controller;
$requested_controller_instance = new $requested_controller;

if( count( $requested_endpoint_parts ) >= 1 && $requested_endpoint_parts[ 0 ] && method_exists( $requested_controller_instance, $requested_endpoint_parts[ 0 ] ) && is_callable( [ $requested_controller_instance, $requested_endpoint_parts[ 0 ] ] ) )
{
    $requested_method = $requested_endpoint_parts[ 0 ];
    unset( $requested_endpoint_parts[ 0 ] );
    $requested_endpoint_parts = array_values( $requested_endpoint_parts );
}
else if( count( $requested_endpoint_parts ) >= 1 && $requested_endpoint_parts[ 0 ] && method_exists( $requested_controller_instance, '_not_found_404' ) )
{
    http_response_code( 404 );
    $requested_method = '_not_found_404';
}
else if( count( $requested_endpoint_parts ) >= 1 && $requested_endpoint_parts[ 0 ] && method_exists( $default_controller_instance, '_not_found_404' ) )
{
    http_response_code( 404 );
    $requested_controller_instance = $default_controller_instance;
    $requested_method = '_not_found_404';
}
else if( count( $requested_endpoint_parts ) >= 1 && $requested_endpoint_parts[ 0 ] )
{
    http_response_code( 404 );
    echo '404 Not Found';
    exit;
}

call_user_func( [ $requested_controller_instance, $requested_method ], $requested_endpoint_parts );
