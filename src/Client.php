<?php

namespace Zeauw\SSCAPIv2;
/**
 * Class Client
 *
 * @package Zeauw\SSCAPIv2
 */
class Client
{
	/**
	 * @var \Zeauw\SSCAPIv2\HttpClient
	 */
	protected $client;
	/**
	 * @var bool
	 */
	protected $testmode = false;
	/**
	 * Client constructor.
	 *
	 * @param $access_token
	 */
	public function __construct($access_token)
	{
		// Create client
		$this->client = new HttpClient($access_token);
	}
	/**
	 * @return string
	 */
	public function getSendname()
	{
		// Get sendname
		return $this->client->get("sendname");
	}
	/**
	 * @return int
	 */
	public function getCredits()
	{
		// Get current credits count
		return $this->client->get("credits");
	}
	/**
	 * @param int $page
	 * @return array
	 */
	public function getHistory($page = 1)
	{
		// Get history
		return $this->client->get("history",["page" => $page]);
	}
	/**
	 * @param string|array $numbers
	 * @param string $msg
	 * @param null|string $sendname
	 * @return bool
	 */
	public function send($numbers,$msg,$sendname = null)
	{
		// Check numbers
		if (!is_array($numbers)) $numbers = [$numbers];
		// Post
		return $this->client->post("send",[
			"numbers" => $numbers,
			"message" => $msg,
			"sendname" => $sendname,
			"testmode" => $this->testmode,
		]);
	}
	/**
	 * @param string $sendname
	 * @return bool
	 */
	public function setSendname($sendname)
	{
		// Post
		return $this->client->post("sendname",["sendname" => $sendname]);
	}
	/**
	 * @return $this
	 */
	public function isTestmode()
	{
		// Set testmode
		$this->testmode = true;
		// Return this object
		return $this;
	}
}