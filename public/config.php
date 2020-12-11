<?php
const DOT_ENV_PATH = '/../.env'; // already related to __DIR__

const LOGS_PATH = __DIR__ . '/../logs';
const ERRORS_LOG = LOGS_PATH . '/errors.log';

const VIEWS_PATH = '/views';
const PAGES = [
    'main' => [
        'path' => __DIR__ . VIEWS_PATH . '/main.phtml',
    ]
];
