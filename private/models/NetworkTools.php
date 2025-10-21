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
        
        if( ! in_array( $type, $this->validTypes ) && $type != "ALL" )
        {
            $return['type'] = 'error';
            $return['message'] = 'Unsuppored Record Type: ' . $type;
            return $return;
        }

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
    	
    	try
    	{
			$answers = array();

			if( $type == "ALL" )
			{
				foreach( $this->validTypes as $validType )
				{
					shuffle( $nameservers );
					
					$resolver = new \Net_DNS2_Resolver( array( "nameservers" => $nameservers ) );
					$response = $resolver->query( $hostname, $validType );

					if( isset( $response->answer ) )
					{
						for( $a = 0; $a < count( $response->answer ); $a++ )
						{
							$response->answer[ $a ] = ( array ) $response->answer[ $a ];
							if( isset( $response->answer[ $a ]['rdata'] ) ) unset( $response->answer[ $a ]['rdata'] );
							if( isset( $response->answer[ $a ]['rdlength'] ) ) unset( $response->answer[ $a ]['rdlength'] );
						}
					}

					$answers[ $validType ][ 'answer' ] = $response->answer;
					$answers[ $validType ][ 'answer_from' ] = $response->answer_from;
				}

				$return['type'] = 'success';
    			$return['answers'] = $answers;
    			return $return;
			}

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
			
    		$response = $resolver->query( $hostname, $type );
    		
    		if( isset( $response->answer ) )
    		{
    		    for( $a = 0; $a < count( $response->answer ); $a++ )
    		    {
    		        $response->answer[ $a ] = ( array ) $response->answer[ $a ];
					if( isset( $response->answer[ $a ]['rdata'] ) ) unset( $response->answer[ $a ]['rdata'] );
					if( isset( $response->answer[ $a ]['rdlength'] ) ) unset( $response->answer[ $a ]['rdlength'] );
    		    }
    		    
    			$return['type'] = 'success';
    			$return['answers'][ $type ]['answer'] = $response->answer;
				$return['answers'][ $type ]['answer_from'] = $response->answer_from;
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

	public function rdns( String $ip_address, array $nameservers = NetworkTools::DEFAULT_NAMESERVERS )
	{
		if( ! $ip_address )
        {
            $return['type'] = 'error';
            $return['message'] = 'IP Address not provided.';
            return $return;
        }

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

		try
    	{
			$answers = array();

			shuffle( $nameservers );
				
			$resolver = new \Net_DNS2_Resolver( array( "nameservers" => $nameservers ) );
			$response = $resolver->query( $ip_address, "PTR" );

			if( isset( $response->answer ) )
			{
				for( $a = 0; $a < count( $response->answer ); $a++ )
				{
					$response->answer[ $a ] = ( array ) $response->answer[ $a ];
					if( isset( $response->answer[ $a ]['rdata'] ) ) unset( $response->answer[ $a ]['rdata'] );
					if( isset( $response->answer[ $a ]['rdlength'] ) ) unset( $response->answer[ $a ]['rdlength'] );
				}
			}

			$answers[ 'PTR' ][ 'answer' ] = $response->answer;
			$answers[ 'PTR' ][ 'answer_from' ] = $response->answer_from;

			$return['type'] = 'success';
			$return['answers'] = $answers;
			return $return;

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
			
    		$response = $resolver->query( $hostname, $type );
    		
    		if( isset( $response->answer ) )
    		{
    		    for( $a = 0; $a < count( $response->answer ); $a++ )
    		    {
    		        $response->answer[ $a ] = ( array ) $response->answer[ $a ];
					if( isset( $response->answer[ $a ]['rdata'] ) ) unset( $response->answer[ $a ]['rdata'] );
					if( isset( $response->answer[ $a ]['rdlength'] ) ) unset( $response->answer[ $a ]['rdlength'] );
    		    }
    		    
    			$return['type'] = 'success';
    			$return['answers'][ 'PTR' ]['answer'] = $response->answer;
				$return['answers'][ 'PTR' ]['answer_from'] = $response->answer_from;
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

	public function friendlyTTL( int $ttl )
	{
		if( $ttl >= 60 * 60 * 24 )
			return $ttl . ' (' . round( $ttl / 60 / 60 / 24 ) . " day" . ( round( $ttl / 60 / 60 / 24 ) > 1 ? 's' : '' ) . ')';
		if( $ttl >= 60 * 60 )
			return $ttl . ' (' . round( $ttl / 60 / 60 ) . " hour" . ( round( $ttl / 60 / 60 ) > 1 ? 's' : '' ) . ')';
		if( $ttl >= 60 )
			return $ttl . ' (' . round( $ttl / 60 ) . " minute" . ( round( $ttl / 60 ) > 1 ? 's' : '' ) . ')';
		return $ttl . " second" . ( $ttl > 1 ? 's' : '' );
	}

	public function whois( String $hostname )
	{
		if( ! $hostname )
        {
            $return['type'] = 'error';
            $return['message'] = 'Hostname not provided.';
            return $return;
        }

		$url = "https://tools.k.io/v1/whois/" . urlencode( $hostname );

		$context = stream_context_create(['http' => ['ignore_errors' => true]]);

		$result = file_get_contents( $url, false, $context );

		if( ! empty( $result ) )
		{
			$return['type'] = 'success';
			$return['result'] = $result;

			return $return;
		}

		$return['type'] = 'error';
		$return['message'] = 'No result returned.';
		$return['http_response_code'] = $http_response_header;

		return $return;
	}

	public static function generateCsrfToken()
    {
        return bin2hex( random_bytes( 35 ) );
    }
}
