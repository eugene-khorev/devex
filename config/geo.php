<?php

return [
    'version' => '1.x',
    'lang' => \Yandex\Geo\Api::LANG_RU,
    'result' => [
        'limit' => env('GEO_RESULT_LIMIT', 6),
    ],
];
