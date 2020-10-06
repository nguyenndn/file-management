<?php

namespace GGPHP\FileMedia\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as LaravelRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class BaseRequest extends LaravelRequest
{
    /**
     * Get the proper failed validation response for the request.
     *
     * @param array $errors     Array errors
     * @param int   $statusCode Status code
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function responseError(array $errors, $statusCode = 400)
    {
        $dataResponse = [
            'message' => head($errors)[0]
        ];

        return response()->json($dataResponse, $statusCode);
    }

    /**
     * Get the proper failed validation response for the request.
     *
     * @param array $errors array errors
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        return $this->responseError($errors);
    }

    /**
     * This method is used to response error message
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    public function failedValidation(Validator $validator)
    {
        $response = [];
        $response['message'] =  trans('lang-fileMedia::messages.common.validationError');

        $data =  [
            'status' => config('constants-fileMedia.HTTP_STATUS_CODE.BAD_REQUEST'),
            'title' => trans('lang-fileMedia::messages.common.validationError'),
        ];

        $errorResponses = function ($errors) use ($data) {
            foreach ($errors as $key => $error) {
                if (!is_array($error)) {
                    $errorResponses[] = [
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

        $response['errors'] = $errorResponses($validator->errors()->toArray());

        throw new HttpResponseException(response()->json($response, 400));
    }
}