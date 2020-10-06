<?php

namespace GGPHP\FileMedia\Exceptions;

use App\Exceptions\Handler;
use Throwable;

class FileMediaException extends Handler
{

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        $data = [
            'status' => $e instanceof HttpException ? $e->getStatusCode() : config('constants.HTTP_STATUS_CODE.SERVER_ERROR'),
            'title' => trans('lang::messages.common.occurError'),
            'errors' => [[
                'title' => trans('lang::messages.common.serverError'),
                'detail' => $e->getMessage() ?: ('An exception of ' . get_class($e)),
            ]],
        ];

        if ($e instanceof AuthorizationException) {
            $data = array_merge($data, [
                'status' => config('constants.HTTP_STATUS_CODE.PERMISSION_DENIED'),
                'errors' => [[
                    'title' => trans('lang::messages.common.permissionDenied'),
                    'detail' => $e->getMessage(),
                ]],
            ]);
        }

        if ($e instanceof UnauthorizedException || $e instanceof AuthenticationException) {
            $data = array_merge($data, [
                'status' => config('constants.HTTP_STATUS_CODE.UNAUTHORIZED'),
                'errors' => [[
                    'title' => trans('lang::messages.common.unauthorized'),
                    'detail' => $e->getMessage() ?: trans('lang::messages.common.unauthorized'),
                ]],
            ]);
        }

        if ($e instanceof ModelNotFoundException) {
            $data = array_merge($data, [
                'status' => config('constants.HTTP_STATUS_CODE.NOT_FOUND'),
                'errors' => [[
                    'title' => trans('lang::messages.common.notFoundError'),
                    'detail' => trans('lang::messages.common.notFoundModel'),
                ]],
            ]);
        }

        if ($e instanceof HttpException) {
            $data = array_merge($data, [
                'status' => $e->getStatusCode(),
                'errors' => [[
                    'title' => trans('lang::messages.common.notFoundError'),
                    'detail' => $e->getMessage() ?: ('An exception of ' . get_class($e)),
                ]],
            ]);
        }

        if ($e instanceof HttpResponseException) {
            $data = array_merge($data, [
                'status' => $e->getResponse()->getStatusCode(),
                'title' => trans('lang::messages.common.validationError'),
            ]);

            $errorResponses = function ($errors) use ($data) {
                foreach ($errors as $key => $error) {
                    if (!is_array($error)) {
                        $errorResponses[] = [
                            'title' => trans('lang::messages.common.badRequest'),
                            'detail' => $error,
                        ];
                    } else {
                        foreach ($error as $detail) {
                            $errorResponses[] = [
                                'title' => $data['title'],
                                'detail' => $detail,
                                'source' => [
                                    'pointer' => $key,
                                ],
                            ];
                        }
                    }
                }
                return $errorResponses;
            };
            $data['errors'] = $errorResponses((array) $e->getResponse()->getContent());
        }

        if ($e instanceof ValidationException) {
            $data = array_merge($data, [
                'status' => config('constants.HTTP_STATUS_CODE.BAD_REQUEST'),
                'title' => trans('lang::messages.common.validationError'),
            ]);

            $errorResponses = function ($errors) use ($data) {
                foreach ($errors as $key => $error) {
                    if (!is_array($error)) {
                        $errorResponses[] = [
                            'title' => trans('lang::messages.common.badRequest'),
                            'detail' => $error,
                        ];
                    } else {
                        foreach ($error as $detail) {
                            $errorResponses[] = [
                                'detail' => $detail,
                                'source' => [
                                    'point' => $key,
                                ],
                            ];
                        }
                    }
                }
                return $errorResponses;
            };
            $data['errors'] = $errorResponses($e->validator->errors()->toArray());
        }

        return response()->json($data, $data['status']);
    }
}
