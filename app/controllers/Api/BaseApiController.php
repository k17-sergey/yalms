<?php

namespace app\controllers\Api;

use Illuminate\Validation\Validator;
use Response;

class BaseApiController extends \Controller
{

	/**
	 * @var null|Validator
	 */
	protected $validator = null;

	/**
	 * @param int    $statusHTTP
	 * @param string $message
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function clientError($statusHTTP = 404, $message = 'Not found')
	{
		switch ($statusHTTP) {
			case 403:
				$message = 'Forbidden';
		}
		return Response::json(array(
				'Status'  => $statusHTTP,
				'Message' => $message
			),
			$statusHTTP
		);
	}

	/**
	 * Сообщение о результате выполнения запроса
	 * (чаще нужно при ошибках; поскольку при удаче — следует выполнить перенаправление...
	 *  сообщение об удачном выполнении нужно при удалении ресурса)
	 *
	 * @param bool   $result
	 * @param string $message
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function requestResult($message = 'Query failed', $result = false)
	{
		$resultMessage = array(
			'Result'  => $result,
			'Message' => $message
		);
		if (!$result && !is_null($this->validator) && $this->validator->fails()) {
			$resultMessage['Errors'] = array(
				'messages' => $this->validator->messages(),
				'failed'   => $this->validator->failed()
			);
		}

		return Response::json($resultMessage);
	}

}