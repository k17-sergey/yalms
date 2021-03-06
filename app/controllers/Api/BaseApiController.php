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
				break;
			case 405:
				$message = 'Method not allowed';
		}
		return Response::json(array(
				'message' => $message,
				'errors'  => array()
			),
			$statusHTTP
		);
	}

	/**
	 * Сообщение об успешном выполнения запроса
	 *
	 * @param string $message
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function responseSuccess($message = 'Success')
	{
		return Response::json(array(
				'message' => $message,
				'errors'  => array()
			)
		);
	}

	/**
	 * Сообщение об ошибках
	 *
	 * @param string $message
	 * @param array  $errors
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function responseError($message = 'Query failed', $errors = array())
	{
		return Response::json(array(
				'message' => $message,
				'errors'  => $errors
			)
		);
	}

}