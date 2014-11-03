<?php

namespace app\controllers\Api;

use Response;

class BaseApiController extends \Controller
{

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
		return Response::json(array(
				'Result'  => $result,
				'Message' => $message
			)
		);
	}

}