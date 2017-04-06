<?php

namespace Zeauw\SSCAPIv2;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Zeauw\SSCAPIv2\Exceptions\SSCAPIv2Exception;

/**
 * Class HttpClient
 *
 * @package Zeauw\SSCAPIv2
 */
class HttpClient
{
	/**
	 * Library constants
	 */
	const ENDPOINT = "https://smsservicecenter.nl/api/v2/";
	const VERSION = "1.0.0";
	/**
	 * @var \GuzzleHttp\Client $client
	 */
	protected $client;
	/**
	 * @var string $access_token
	 */
	protected $access_token;
	/**
	 * HttpClient constructor.
	 *
	 * @param string $access_token
	 */
	public function __construct($access_token)
	{
		// Store access token
		$this->access_token = $access_token;
		// Setup client
		$this->client = new Client(["base_uri" => self::ENDPOINT]);
	}
	/**
	 * @param string $endpoint
	 * @param array $params
	 * @return mixed
	 */
	public function get($endpoint,array $params = [])
	{
		// Create the request
		$request = $this->createRequest("GET",$endpoint,null,$params);
		// Send request and return the formatted response
		return $this->parseResponse($this->client->send($request));
	}
	/**
	 * @param string $endpoint
	 * @param array $params
	 * @return mixed
	 */
	public function post($endpoint,array $params = [])
	{
		// Create request
		$request = $this->createRequest("POST",$endpoint,json_encode($params,JSON_FORCE_OBJECT));
		// Send request and return the formatted response
		return $this->parseResponse($this->client->send($request));
	}
	/**
	 * Creates a request.
	 *
	 * @param string $method
	 * @param string $endpoint
	 * @param null|string $body
	 * @param array $params
	 * @param array $headers
	 * @return \GuzzleHttp\Psr7\Request
	 */
	private function createRequest($method = "GET",$endpoint,$body = null,array $params = [],array $headers = [])
	{
		// Set headers
		$headers = array_merge($headers,[
			"Accept" => "application/json",
			"Content-Type" => "application/json",
			"Authorization" => "Bearer " . $this->access_token,
			"User-Agent" => "SSCAPIv2 PHP Client/v" . self::VERSION,
		]);
		// Check params
		if (count($params) > 0) $endpoint .= "?" . http_build_query($params);
		// Create request
		return new Request($method,$endpoint,$headers,$body);
	}
	/**
	 * @param \GuzzleHttp\Psr7\Response $response
	 * @return mixed
	 * @throws \Zeauw\SSCAPIv2\Exceptions\SSCAPIv2Exception
	 */
	private function parseResponse(Response $response)
	{
		// Store responsebody as an array
		$json = json_decode($response->getBody(),true);
		// Check body. If null, PHP was unable to decode the JSON string (which it probably isnt).
		if (is_null($json)) throw new SSCAPIv2Exception("An error occurred while calling the SMS Service Center API.");
		// Check status
		if (!isset($json["status"])) throw new SSCAPIv2Exception("Invalid results received from the API.");
		// Check if error
		if ($json["status"] == "error") throw new SSCAPIv2Exception(sprintf("An error occurred while calling the SMS Service Center API: %s",$json["message"]));
		// If details are missing from this point on, wrong API results are received.
		if (!isset($json["details"])) throw new SSCAPIv2Exception("Something is wrong!");
		// Return
		return $json["details"];
	}
}