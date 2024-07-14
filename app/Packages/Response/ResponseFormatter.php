<?php

namespace App\Packages\Response;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;

class ResponseFormatter
{
    /**
     * Set response data.
     * @param mixed $data
     * @param string|null $message
     * @param string|null $status
     * @param int $httpStatusCode
     * @return array
     */
    private static function makeResponse(mixed $data = array(), ?string $message = null, int $httpStatusCode = 200,?bool $showMessage = true): array
    {

        $response['httpStatusCode'] = $httpStatusCode;
        $response['data']['message'] = $message;
        $response['data']['show_message'] = $showMessage;

        if (!is_array($data) && !is_null($data) && !($data instanceof Model) && !is_string($data)) {
            $data = json_decode($data->response()->content(), true);
        }

        if (isset($data['meta'])) {
            unset($data['meta']['links']);
            $response['data']['meta'] = $data['meta'];
            $response['data']['meta']['next_page'] = $data['meta']['last_page'] > $data['meta']['current_page'] ? $data['meta']['current_page'] + 1 : null;
            $response['data']['meta']['previous_page'] = $data['meta']['current_page'] > 1 ? $data['meta']['current_page'] - 1 : null;
        }

        if ($httpStatusCode >= 400) {
            $error = (is_array($data) && array_key_exists('data', $data)) ? $data['data'] : $data;
            if (!is_array($error)){
                $response['data']['error'][] = $error;
            }else{
                $response['data']['error'] = $error;
            }
        } else {
            $data = (is_array($data) && isset($data['data']) && is_array($data['data'])) ? $data['data'] : $data;
            if (!is_array($data)){
                $response['data']['data'][]= $data;
            }else{
                $response['data']['data']= $data;
            }
        }



        return $response;
    }

    /**
     * Give informational response.
     * @param mixed|null $data
     * @param mixed|null $message
     * @param int $code
     * @param int $httpStatusCode
     * @return array
     */
    public static function informational(mixed $data = array(), mixed $message = null, int $httpStatusCode = 100,?bool $showMessage = true): array
    {
        return self::makeResponse($data, $message, $httpStatusCode,$showMessage);
    }

    /**
     * Give success response.
     * @param mixed|null $data
     * @param mixed|null $message
     * @param int $httpStatusCode
     * @return array
     */
    public static function success(mixed $data = array(), mixed $message = null, int $httpStatusCode = 200,?bool $showMessage = true): array
    {
        return self::makeResponse($data, $message, $httpStatusCode,$showMessage);
    }

    /**
     * Give error response.
     * @param null $data
     * @param null $message
     * @param int $httpStatusCode
     * @return array
     */
    public static function error(mixed $data = array(), mixed $message = null, int $httpStatusCode = 400,?bool $showMessage = true): array
    {
        return self::makeResponse($data, $message, $httpStatusCode,$showMessage);
    }

    /**
     * Give redirection response.
     * @param null $data
     * @param null $message
     * @param int $httpStatusCode
     * @return array
     */
    public static function redirection(mixed $data = array(), mixed $message = null, int $httpStatusCode = 300,?bool $showMessage = true): array
    {
        return self::makeResponse($data, $message, $httpStatusCode,$showMessage);
    }

    /**
     * @param mixed|null $data
     * @param mixed|null $message
     * @return array
     */
    public static function create(mixed $data = array(), mixed $message = null): array
    {
        return self::success(
            data: $data,
            message: $message,
            httpStatusCode: Response::HTTP_CREATED,
        );
    }

    /**
     * @param mixed|null $message
     * @return array
     */
    public static function noContent(mixed $message = null): array
    {
        return self::success(
            message: $message,
            httpStatusCode: Response::HTTP_NO_CONTENT,
        );
    }

    /**
     * @param mixed|null $error
     * @return array
     */
    public static function entity(mixed $error = null): array
    {
        return static::error(
            data: $error,
            httpStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * @param string|null $message
     * @return array
     */
    public static function tooManyRequest(string $message = null): array
    {
        return static::error(
            message: $message,
            httpStatusCode: Response::HTTP_TOO_MANY_REQUESTS
        );
    }

    /**
     * @param mixed|null $error
     * @param string|null $message
     * @return array
     */
    public static function notFound(mixed $error = null, string $message = null): array
    {
        return static::error(
            data: $error,
            message: $message,
            httpStatusCode: Response::HTTP_NOT_FOUND
        );
    }

    /**
     * @param mixed|null $error
     * @param string|null $message
     * @return array
     */
    public static function methodNotAllowed(string $message = null): array
    {
        return static::error(
            message: $message,
            httpStatusCode: Response::HTTP_METHOD_NOT_ALLOWED
        );
    }

    /**
     * @param mixed|null $message
     * @return array
     */
    public static function unauthorized(mixed $message = null): array
    {
        return static::error(
            message: $message,
            httpStatusCode: Response::HTTP_UNAUTHORIZED,
            showMessage: false
        );
    }

    /**
     * @param mixed|null $message
     * @return array
     */
    public static function notAcceptable(mixed $message = null): array
    {
        return static::error(
            message: $message,
            httpStatusCode: Response::HTTP_NOT_ACCEPTABLE,
        );
    }

    /**
     * @param mixed|null $message
     * @return array
     */
    public static function forbidden(mixed $message = null): array
    {
        return static::error(
            message: $message,
            httpStatusCode: Response::HTTP_FORBIDDEN,
            showMessage: false
        );
    }

    /**
     * @param mixed|null $message
     * @return array
     */
    public static function gatewayTimeout(mixed $message = null): array
    {
        return static::error(
            message: $message,
            httpStatusCode: Response::HTTP_GATEWAY_TIMEOUT,
        );
    }

    /**
     * @param mixed|null $message
     * @return array
     */
    public static function typeError(mixed $message = null): array
    {
        return static::error(
            message: $message,
            httpStatusCode: Response::HTTP_BAD_REQUEST,
            showMessage: false
        );
    }

    /**
     * @param mixed|null $message
     * @return array
     */
    public static function serviceUnavailable(mixed $message = null): array
    {
        return static::error(
            message: $message,
            httpStatusCode: Response::HTTP_SERVICE_UNAVAILABLE,
        );
    }

    /**
     * @param mixed|null $message
     * @return array
     */
    public static function internalServiceError(mixed $message = null): array
    {
        return static::error(
            message: $message,
            httpStatusCode: Response::HTTP_INTERNAL_SERVER_ERROR,
        );
    }
    /**
     * @param mixed|null $message
     * @return array
     */
    public function accepted(mixed $message = null): array
    {
        return self::success(
            message: $message,
            httpStatusCode: Response::HTTP_ACCEPTED,
        );
    }

    /**
     * @return array
     */
    public function conflict(): array
    {
        return static::error(
            httpStatusCode: Response::HTTP_CONFLICT
        );
    }
}

