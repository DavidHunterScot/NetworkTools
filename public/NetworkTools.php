<?php

class NetworkTools
{
    public const DEFAULT_NAMESERVERS = array( '1.1.1.1', '1.0.0.1' );
    
    private $validTypes = array( "SOA", "NS", "A", "AAAA", "CNAME", "MX", "TXT" );
    
    public function dns( String $hostname, String $type = "A", array $nameservers = NetworkTools::DEFAULT_NAMESERVERS )
    {
        $return = array();
        
        if( ! $hostname )
        {
            $return['type'] = 'error';
            $return['message'] = 'Hostname not provided.';
            return $return;
        }
        
        if( ! in_array( $type, $this->validTypes ) )
        {
            $return['type'] = 'error';
            $return['message'] = 'Unsuppored Record Type: ' . $type;
            return $return;
        }

		shuffle( $nameservers );
        
		// Download latest Net_DNS2 from https://pear.php.net/package/Net_DNS2
		// Place the "Net" dir next to this project.
        $path_to_netdns2 = 'Net/DNS2.php';
        
        require_once $path_to_netdns2;
    	
    	if( ! class_exists( "\\Net_DNS2_Resolver" ) )
    	{
    	    $return['type'] = 'error';
    	    $return['message'] = 'Resolver class not found.';
    	    return $return;
    	}
    	
    	$resolver = new \Net_DNS2_Resolver( array( "nameservers" => $nameservers ) );
    	
    	try
    	{
    		$response = $resolver->query( $hostname, $type );
    		
    		if( isset( $response->answer ) )
    		{
    		    for( $a = 0; $a < count( $response->answer ); $a++ )
    		    {
    		        $response->answer[ $a ] = ( array ) $response->answer[ $a ];
    		    }
    		    
    			$return['type'] = 'success';
    			$return['answer'] = $response->answer;
				$return['answer_from'] = $response->answer_from;
    			return $return;
    		}
    		
    		$return['type'] = 'output';
    		$return['response'] = $response;
    		return $return;
    	}
    	catch( Net_DNS2_Exception $ndns2e )
    	{
    		$return['type'] = 'error';
    		$return['message'] = $ndns2e->getMessage();
    		return $return;
    	}
    }
    
    public function getValidTypes()
    {
        return $this->validTypes;
    }
}
