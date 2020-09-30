<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'HTTP_STATUS_CODE' => [
        'NOT_FOUND'            => 404,
        'BAD_REQUEST'          => 400,
        'SERVER_ERROR'         => 500,
        'METHOD_NOT_ALLOWED'   => 405,
        'UNAUTHORIZED'         => 401,
        'PERMISSION_DENIED'    => 403,
        'UNPROCESSABLE_ENTITY' => 422,
        'NOT_ACCEPTABLE'       => 406,
        'SUCCESS'              => 200,
    ],
    'SEARCH_VALUES_DEFAULT' => [
        'LIMIT'             => 1000,
        'PAGE'              => 1,
        'LIMIT_ZERO'        => 0,
        'LIMIT_RANDOM_USER' => 50,
        'SORTED_BY_DEFAULT' => 'ASC',
    ],
    'ABSENT_TYPE' => [
        'ON', 'OFF'
    ],
    'ABSENT' => [
        'APPROVED','DECLINED'
    ]
];
