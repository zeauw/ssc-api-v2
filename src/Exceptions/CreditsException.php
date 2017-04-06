<?php

namespace Zeauw\SSCAPIv2\Exceptions;
use Exception;
use Throwable;

/**
 * Class CreditsException
 *
 * @package Zeauw\SSCAPIv2\Exceptions
 */
class CreditsException extends Exception
{
	/**
	 * @var int $remaining
	 */
	protected $remaining;
	/**
	 * @var int $required
	 */
	protected $required;
	/**
	 * CreditsException constructor.
	 *
	 * @param string $message
	 * @param int $remaining
	 * @param int $required
	 * @param int $code
	 * @param \Throwable|null $previous
	 */
	public function __construct($message = "",$remaining = 0,$required = 0,$code = 0,Throwable $previous = null)
	{
		// Call parent constructor
		parent::__construct($message,$code,$previous);
		// Store remaining
		$this->remaining = $remaining;
		// Store required
		$this->required = $required;
	}
	/**
	 * @return int
	 */
	public function getRemainingCredits()
	{
		// Returns the stored amount of credits available within the system.
		return $this->remaining;
	}
	/**
	 * @return int
	 */
	public function getRequiredCredits()
	{
		// Returns the stored required amount of credits for the last request.
		return $this->required;
	}
}