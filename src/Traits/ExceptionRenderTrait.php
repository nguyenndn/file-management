<?php

namespace App\Traits;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\UnprocessableEntityException;

trait ExceptionRenderTrait
{

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $data = [
            'status' => $e instanceof HttpException ? $e->getStatusCode() : config('constants.HTTP_STATUS_CODE.SERVER_ERROR'),
            'title' => trans('messages.common.occurError'),
            'errors' => [[
                'title' => trans('messages.common.serverError'),
                'detail' => $e->getMessage()?:('An exception of '.get_class_name($e)),
            ]]
        ];

        if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
            $data = array_merge($data, [
                'status' => config('constants.HTTP_STATUS_CODE.PERMISSION_DENIED'),
                'errors' => [[
                    'title' => trans('messages.common.permissionDenied'),
                    'detail' => $e->getMessage(),
                ]]
            ]);
        }

        if ($e instanceof \Illuminate\Validation\UnauthorizedException || $e instanceof \Illuminate\Auth\AuthenticationException) {
            $data = array_merge($data, [
                'status' => config('constants.HTTP_STATUS_CODE.UNAUTHORIZED'),
                'errors' => [[
                    'title' => trans('messages.common.unauthorized'),
                    'detail' => $e->getMessage() ?: trans('messages.common.unauthorized'),
                ]]
            ]);
        }

        if ($e instanceof ModelNotFoundException) {
            $data = array_merge($data, [
                'status' => config('constants.HTTP_STATUS_CODE.NOT_FOUND'),
                'errors' => [[
                    'title' => trans('messages.common.notFoundError'),
                    'detail' => trans('messages.common.notFoundModel'),
                ]]
            ]);
        }

        if ($e instanceof HttpException) {
            $data = array_merge($data, [
                'status' => $e->getStatusCode(),
                'errors' => [[
                    'title' => trans('messages.common.notFoundError'),
                    'detail' => $e->getMessage() ?: ('An exception of '.get_class_name($e)),
                ]]
            ]);
        }

        if ($e instanceof HttpResponseException) {
            $data = array_merge($data, [
                'status' => $e->getResponse()->status(),
                'title' => trans('messages.common.validationError'),
            ]);

            $errorResponses = function ($errors) use ($data) {
                foreach ($errors as $key => $error) {
                    if (!is_array($error)) {
                        $errorResponses[] = [
                            'title'     => trans('messages.common.badRequest'),
                            'detail'    => $error,
                        ];
                    } else {
                        foreach ($error as $detail) {
                            $errorResponses[] = [
                                'title'     => $data['title'],
                                'detail'    => $detail,
                                'source' => [
                                    'pointer' => $key
                                ]
                            ];
                        }
                    }
                }
                return $errorResponses;
            };
            $data['errors'] = $errorResponses((array)$e->getResponse()->getData());
        }

        if ($e instanceof \Illuminate\Validation\ValidationException) {
            $data = array_merge($data, [
                'status' => config('constants.HTTP_STATUS_CODE.BAD_REQUEST'),
                'title' => trans('messages.common.validationError'),
            ]);

            $errorResponses = function ($errors) use ($data) {
                foreach ($errors as $key => $error) {
                    if (!is_array($error)) {
                        $errorResponses[] = [
                            'title'     => trans('messages.common.badRequest'),
                            'detail'    => $error,
                        ];
                    } else {
                        foreach ($error as $detail) {
                            $errorResponses[] = [
                                'title'     => $data['title'],
                                'detail'    => $detail,
                                'source' => [
                                    'pointer' => $key
                                ]
                            ];
                        }
                    }
                }
                return $errorResponses;
            };
            $data['errors'] = $errorResponses($e->validator->errors()->toArray());
        }

        if ($e instanceof UnprocessableEntityException) {
            $data = array_merge($data, [
                'status' => config('constants.HTTP_STATUS_CODE.UNPROCESSABLE_ENTITY'),
                'errors' => [[
                    'title' => trans('messages.common.unprocessableEntity'),
                    'detail' => $e->getMessage(),
                ]]
            ]);
        }
        return response()->json($data, $data['status']);
    }
}
