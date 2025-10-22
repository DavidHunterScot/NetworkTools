<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Controller.php';

class DefaultController extends Controller
{
    public function index()
    {
        $this->view( 'index', array( 'tool' => '' ) );
    }

    public function dns()
    {
        $networkTools = $this->model( 'NetworkTools' );
        $hostname = '';
        $type = '';
        $nameservers = NetworkTools::DEFAULT_NAMESERVERS;
        $csrf_token = '';
        $csrf_passed = true;

        if( isset( $_POST[ 'hostname' ] ) && $_POST[ 'hostname' ] )
            $hostname = $_POST[ 'hostname' ];
        if( isset( $_POST[ 'type' ] ) && $_POST[ 'type' ] )
            $type = $_POST[ 'type' ];
        if( isset( $_POST[ 'nameservers' ] ) && $_POST[ 'nameservers' ] )
            $nameservers = explode( ' ', $_POST[ 'nameservers' ] );
        if( isset( $_POST[ 'csrf_token' ] ) && $_POST[ 'csrf_token' ] )
            $csrf_token = $_POST[ 'csrf_token' ];

        if( $_SERVER[ 'REQUEST_METHOD' ] == "POST" && ( ! $csrf_token || ! isset( $_SESSION[ 'csrf_token' ] ) || $csrf_token != $_SESSION[ 'csrf_token' ] ) )
        {
            $csrf_passed = false;
            $_SESSION[ 'error' ] = 'CSRF Token Missmatch! Please try again.';
        }

        $_SESSION[ 'csrf_token' ] = NetworkTools::generateCsrfToken();

        $result = array();

        if( $csrf_passed )
            $result = $networkTools->dns( $hostname, $type, $nameservers );

        if( $_SERVER[ 'REQUEST_METHOD' ] == "POST" && isset( $result[ 'type' ] ) && $result[ 'type' ] == 'error' && ! isset( $_SESSION[ 'error' ] ) && isset( $result[ 'message' ] ) )
            $_SESSION[ 'error' ] = $result[ 'message' ];

        $this->view( 'dns', array( 'tool' => 'dns', 'networkTools' => $networkTools, 'hostname' => $hostname, 'type' => $type, 'nameservers' => $nameservers, 'result' => $result ) );
    }

    public function rdns()
    {
        $networkTools = $this->model( 'NetworkTools' );
        $ip_address = '';
        $nameservers = NetworkTools::DEFAULT_NAMESERVERS;
        $csrf_token = '';
        $csrf_passed = true;

        if( isset( $_POST[ 'ip_address' ] ) && $_POST[ 'ip_address' ] )
            $ip_address = $_POST[ 'ip_address' ];
        if( isset( $_POST[ 'nameservers' ] ) && $_POST[ 'nameservers' ] )
            $nameservers = explode( ' ', $_POST[ 'nameservers' ] );
        if( isset( $_POST[ 'csrf_token' ] ) && $_POST[ 'csrf_token' ] )
            $csrf_token = $_POST[ 'csrf_token' ];

        if( $_SERVER[ 'REQUEST_METHOD' ] == "POST" && ( ! $csrf_token || ! isset( $_SESSION[ 'csrf_token' ] ) || $csrf_token != $_SESSION[ 'csrf_token' ] ) )
        {
            $csrf_passed = false;
            $_SESSION[ 'error' ] = 'CSRF Token Missmatch! Please try again.';
        }

        $result = array();

        if( $csrf_passed )
            $result = $networkTools->rdns( $ip_address, $nameservers );

        $_SESSION[ 'csrf_token' ] = NetworkTools::generateCsrfToken();

        if( $_SERVER[ 'REQUEST_METHOD' ] == "POST" && isset( $result[ 'type' ] ) && $result[ 'type' ] == 'error' && ! isset( $_SESSION[ 'error' ] ) && isset( $result[ 'message' ] ) )
            $_SESSION[ 'error' ] = $result[ 'message' ];

        $this->view( 'rdns', array( 'tool' => 'rdns', 'networkTools' => $networkTools, 'ip_address' => $ip_address, 'nameservers' => $nameservers, 'result' => $result ) );
    }

    public function whois()
    {
        $networkTools = $this->model( 'NetworkTools' );
        $hostname = '';
        $csrf_token = '';
        $csrf_passed = true;

        if( isset( $_POST[ 'hostname' ] ) && $_POST[ 'hostname' ] )
            $hostname = $_POST[ 'hostname' ];
        if( isset( $_POST[ 'csrf_token' ] ) && $_POST[ 'csrf_token' ] )
            $csrf_token = $_POST[ 'csrf_token' ];

        if( $_SERVER[ 'REQUEST_METHOD' ] == "POST" && ( ! $csrf_token || ! isset( $_SESSION[ 'csrf_token' ] ) || $csrf_token != $_SESSION[ 'csrf_token' ] ) )
        {
            $csrf_passed = false;
            $_SESSION[ 'error' ] = 'CSRF Token Missmatch! Please try again.';
        }

        $result = array();

        if( $csrf_passed )
            $result = $networkTools->whois( $hostname );

        $_SESSION[ 'csrf_token' ] = NetworkTools::generateCsrfToken();

        if( $_SERVER[ 'REQUEST_METHOD' ] == "POST" && isset( $result[ 'type' ] ) && $result[ 'type' ] == 'error' && ! isset( $_SESSION[ 'error' ] ) && isset( $result[ 'message' ] ) )
            $_SESSION[ 'error' ] = $result[ 'message' ];

        $this->view( 'whois', array( 'tool' => 'whois', 'networkTools' => $networkTools, 'hostname' => $hostname, 'result' => $result ) );
    }

    public function _not_found_404()
    {
        $this->view( '404' );
    }
}