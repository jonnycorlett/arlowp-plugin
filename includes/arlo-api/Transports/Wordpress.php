<?php
namespace ArloAPI\Transports;

// load main Transport class for extending
require_once 'Transport.php';

// now use it
use ArloAPI\Transports\Transport;

class Wordpress extends Transport
{
	/**
	 * core request function
	 *
	 * used as the main communication layer between API and local code
	 *
	 * @param string $platform_name defines the platform we are getting data from
	 * @param string $method defines the method for the request
	 *
	 * @return void
	 */
	public function request($platform_name, $path, $post_data=null, $public=true, $plugin_version = '', $new_url_tructure = false) {
		$args = func_get_args();
		$cache_key = md5(serialize($args));
		
		if($cached = wp_cache_get($cache_key, 'ArloAPI')) {
			return $cached;
		}
		
		$url = $this->getRemoteURL($platform_name, $public, $new_url_tructure) . $path;
				
		try {
			$response = wp_remote_request( $url, array(
				'headers' => array(
					'X-Plugin-Version' => $plugin_version,
				),
				'compress'    => true,
                                'decompress'  => true,
                                'stream'      => false,
				'timeout'     => $this->getRequestTimeout(),
				'method'	  => (is_null($post_data)) ? 'GET' : 'POST',
			) );
			
			if(is_wp_error($response)) {
				$message = reset(reset($response->errors));
				throw new \Exception($message);
			}
			
			if(empty($response) || !isset($response['response']) || empty($response['body'])) {
				throw new \Exception('Invalid Arlo Response.');
			} else if($response['response']['code'] != 200) {
				$body = json_decode($response['body'], true);			
				throw new \Exception('Error code ' . $response['response']['code'] . ': ' . $response['response']['message'] . (!empty($body['Message']) ?  ' ' . $body['Message'] : ''));
			}
		} catch(\Exception $e) {
			// trigger an error
			// not a good idea - not the place to log errors - will just end up filling an error_log
	        /*$trace = debug_backtrace();
	        trigger_error(
	            'Arlo API response error ' .
	            ' in ' . $trace[0]['file'] .
	            ' on line ' . $trace[0]['line'] . 
	            ' with message ' . $e->getMessage(),
            E_USER_NOTICE);*/
            
            //pass on the exception to catch framework-side
            throw $e;
		}
		
		$json = json_decode($response['body']);
		
		wp_cache_add( $cache_key, $json, 'ArloAPI', $this->getCacheTime() );
		
		return $json;
	}
}