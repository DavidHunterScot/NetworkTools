<?php

include_once __DIR__ . DIRECTORY_SEPARATOR . 'NetworkTools.php';

$tool = isset( $_GET['tool'] ) ? $_GET['tool'] : '';
$networkTools = new NetworkTools;

if( ! isset( $endpoint ) )
    $endpoint = isset( $_REQUEST['endpoint'] ) ? $_REQUEST['endpoint'] : "";

$tool = "";

if( $endpoint )
{
    $endpoint_parts = explode( '/', $endpoint );

    if( isset( $endpoint_parts[ 0 ] ) )
    {
        $tool = $endpoint_parts[ 0 ];
        
        if( method_exists( $networkTools, $endpoint_parts[ 0 ] ) )
        {
            unset( $endpoint_parts[ 0 ] );
            $endpoint_parts = array_values( $endpoint_parts );
        }
    }
}

if( isset( $_GET['api'] ) )
    header( "Content-Type: application/json" );

if( $tool == "" )
{
    $output[ 'type' ] = 'info';
    $output[ 'message' ] = 'Welcome to Network Tools! A free service to help you with your networking needs.';

    $api_result = $output;

    if( isset( $_GET['api'] ) )
        die( json_encode( $api_result ) );
}
elseif( $tool == "dns" )
{
    $hostname = isset( $endpoint_parts[ 0 ] ) ? $endpoint_parts[ 0 ] : "";
    $type = isset( $endpoint_parts[ 1 ] ) ? $endpoint_parts[ 1 ] : "";
    $nameservers = isset( $endpoint_parts[ 2 ] ) ? explode( " ", $endpoint_parts[ 2 ] ) : NetworkTools::DEFAULT_NAMESERVERS;

    $api_result = $networkTools->dns( $hostname, $type, $nameservers );

    if( isset( $_GET['api'] ) )
        die( json_encode( $api_result ) );
}
elseif( $tool == "rdns" )
{
    $ip_address = isset( $endpoint_parts[ 0 ] ) ? $endpoint_parts[ 0 ] : "";
    $nameservers = isset( $endpoint_parts[ 1 ] ) ? explode( " ", $endpoint_parts[ 1 ] ) : NetworkTools::DEFAULT_NAMESERVERS;

    $api_result = $networkTools->rdns( $ip_address, $nameservers );

    if( isset( $_GET['api'] ) )
        die( json_encode( $api_result ) );
}
elseif( $tool == "whois" )
{
    $hostname = isset( $endpoint_parts[ 0 ] ) ? $endpoint_parts[ 0 ] : "";

    $api_result = $networkTools->whois( $hostname );

    if( isset( $_GET['api'] ) )
        die( json_encode( $api_result ) );
}
else
{
    $api_result = array( 'type' => 'error', 'message' => 'Requested tool does not exist: ' . $tool );

    if( isset( $_GET['api'] ) )
    {
        http_response_code( 404 );
        die( json_encode( $api_result ) );
    }
}
