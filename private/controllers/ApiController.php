<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Controller.php';

class ApiController extends Controller
{
    public function index()
    {
        $output = array();

        $output[ 'type' ] = 'info';
        $output[ 'message' ] = 'Welcome to Network Tools! A free service to help you with your networking needs.';

        header( "Content-Type: text/plain" );
        echo json_encode( $output );
    }

    public function dns()
    {
        $params = func_get_args();
        $params = $params[ 0 ];

        $networkTools = $this->model( 'NetworkTools' );

        $hostname = '';
        $type = 'ALL';
        $nameservers = NetworkTools::DEFAULT_NAMESERVERS;

        if( isset( $params[ 0 ] ) && $params[ 0 ] )
            $hostname = $params[ 0 ];
        if( isset( $params[ 1 ] ) && $params[ 1 ] )
            $type = $params[ 1 ];
        if( isset( $params[ 2 ] ) && $params[ 2 ] )
            $nameservers = explode( $params[ 2 ] );

        $result = $networkTools->dns( $hostname, $type, $nameservers );

        header( "Content-Type: text/plain" );
        echo json_encode( $result );
    }

    public function rdns()
    {
        $params = func_get_args();
        $params = $params[ 0 ];

        $networkTools = $this->model( 'NetworkTools' );

        $ip_address = '';
        $nameservers = NetworkTools::DEFAULT_NAMESERVERS;

        if( isset( $params[ 0 ] ) && $params[ 0 ] )
            $ip_address = $params[ 0 ];
        if( isset( $params[ 1 ] ) && $params[ 1 ] )
            $nameservers = explode( $params[ 1 ] );

        $result = $networkTools->rdns( $ip_address, $nameservers );

        header( "Content-Type: text/plain" );
        echo json_encode( $result );
    }

    public function whois()
    {
        $params = func_get_args();
        $params = $params[ 0 ];

        $networkTools = $this->model( 'NetworkTools' );

        $hostname = '';

        if( isset( $params[ 0 ] ) && $params[ 0 ] )
            $hostname = $params[ 0 ];

        $result = $networkTools->whois( $hostname );

        header( "Content-Type: text/plain" );
        echo json_encode( $result );
    }

    public function _not_found_404()
    {
        $output = array();

        $output[ 'type' ] = 'error';
        $output[ 'message' ] = 'Error 404: The requested API endpoint does not exist.';
        
        header( "Content-Type: text/plain" );
        echo json_encode( $output );
    }
}