<?php

namespace GGPHP\FileMedia\Traits;

use Auth;
use Config;

trait ResponseTrait
{
    /**
     * Return success response
     *
     * @param any $result
     * @param string $message
     * @param array $options
     * @return \Illuminate\Http\Response
     */
    public function success($result, $message, $options = [])
    {
        $isShowData            = true;
        $isContainByDataString = false;
        $code                  = 200;
        if (!empty($options) && array_key_exists('isShowData', $options)) {
            $isShowData = $options['isShowData'];
        }
        if (!empty($options) && array_key_exists('isContainByDataString', $options)) {
            $isContainByDataString = $options['isContainByDataString'];
        }
        if (!empty($options) && array_key_exists('code', $options)) {
            $code = $options['code'];
        }
        $response = [
            'title'  => $message
        ];
        if ($isShowData) {
            if ($isContainByDataString) {
                $result = ['data' => (object) $result];
            }
            $response = array_merge($response, $result);
        }
        return response()->json($response, $code);
    }
    
    /**
     * Return error response
     *
     * @param string $error
     * @param array/string $errorMessages
     * @param integer $code
     * @return \Illuminate\Http\Response
     */
    public function error($error, $message = [], $code = 404)
    {
        $response = [
            'title'  => $error,
            'errors' => [],
        ];
        if (!empty($message) && is_string($message)) {
            $response['errors'][] = [
                'title' => $message,
                'detail' => $message
            ];
        } else {
            $detailErrors = array_unique(array_flatten(is_array($message) ? $message : $message->toArray()));
            foreach ($detailErrors as $detailError) {
                $response['errors'][] = [
                    'title' => $detailError,
                    'detail' => $detailError
                ];
            }
        }
        if (empty($message)) {
            $response['errors'][] = [
                'title' => $error,
                'detail' => $error
            ];
        }
        return response()->json($response, $code);
    }
}