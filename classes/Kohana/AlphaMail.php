<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Simple wrapper for AlphaMail library
 *
 * @author	 Mario Colque <colquemario@gmail.com> @colkito
 * @copyright  Copyright (c) 2013 Mario Colque
 * @license	GPLv3 License, see https://www.gnu.org/licenses/gpl-3.0.html
 */

use AlphaMail\Client\EmailService;
use AlphaMail\Client\Entities\EmailContact;
use AlphaMail\Client\Entities\EmailMessagePayload;
use AlphaMail\Client\Exceptions\ServiceException;

class Kohana_AlphaMail {

	/**
	 * API Token
	 * @var string
	 */
	protected $_api_token;

	/**
	 * Sender Name
	 * @var string
	 */
	protected $_sender_name;

	/**
	 * Sender Email
	 * @var string
	 */
	protected $_sender_email;

	/**
	 * Success message returned when email was sent
	 * @var string
	 */
	protected $_success_msg;

	/**
	 * Error message returned when email was not sent
	 * @var string
	 */
	protected $_error_msg;

	/**
	 * Load the AlphaMail PHP library and configure it from the config
	 * file or the provided array argument.
	 *
	 * @param   array  $config
	 * @return  object
	 */
	public function __construct(array $config = NULL)
	{
		require_once Kohana::find_file('vendor', 'AlphaMail/Autoloader');

		AlphaMail\Autoloader::register();

		if (empty($config))
		{
			$config = Kohana::$config->load('alphamail');
		}

		$this->_api_token = $config['api_token'];
		$this->_sender_name = $config['sender_name'];
		$this->_sender_email = $config['sender_email'];
		$this->_success_msg = $config['success_msg'];
		$this->_error_msg = $config['error_msg'];
	}

	/**
	 * Returns array with messages.
	 *
	 * @param   string  $to_name
	 * @param   string  $to_email
	 * @param   int     $project_id
	 * @param   array   $message
	 * @return  array
	 */
	public function send($to_name = NULL, $to_email = NULL, $project_id = NULL, array $message = NULL)
	{
		$email_service = new EmailService($this->_api_token);

		$payload = EmailMessagePayload::create()
			->setProjectId($project_id) // ID of the AlphaMail project you want to send with
			->setSender(new EmailContact($this->_sender_name, $this->_sender_email))
			->setReceiver(new EmailContact($to_name, $to_email))
			->setBodyObject($message);

		try
		{
			$response = $email_service->queue($payload);
			$result = array(
				'success_msg' => $this->_success_msg,
				'result' => $response->result
				);
		}
		catch(ServiceException $exception)
		{
			$result = array(
				'error_msg' => $this->_error_msg,
				'error_detail' =>$exception->getMessage(),
				'error_code' => $exception->getErrorCode()
				);
		}

		return $result;
	}

}