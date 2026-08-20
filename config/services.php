<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'huggingface' => [
        'api_key' => env('HUGGINGFACE_API_KEY'),
        'vton_url' => env('VTON_API_URL', 'https://api-inference.huggingface.co/models/yisol/IDM-VTON'),
    ],

    'segmind' => [
        'api_key' => env('SEGMIND_API_KEY'),
    ],

    'fal' => [
        'api_key' => env('FAL_KEY'),
    ],

    'replicate' => [
        'api_key' => env('REPLICATE_API_TOKEN'),
    ],

    'fashn' => [
        'api_key' => env('FASHN_API_KEY'),
    ],

    'banana' => [
        'api_key'   => env('BANANA_API_KEY'),
        'model_key' => env('BANANA_MODEL_KEY'),
    ],

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY', env('BANANA_API_KEY')),
    ],

    'bfl' => [
        'api_key' => env('BFL_API_KEY'),
    ],

    'together' => [
        'api_key' => env('TOGETHER_API_KEY'),
    ],

    'catvton' => [
        'enabled'        => env('CATVTON_ENABLED', true),
        'steps'          => (int) env('CATVTON_STEPS', 30),
        'guidance_scale' => (float) env('CATVTON_CFG', 2.5),
        'seed'           => (int) env('CATVTON_SEED', 42),
        'python_path'    => env('PYTHON_PATH', 'python'),
        'timeout'        => (int) env('CATVTON_TIMEOUT', 180),
    ],

];
