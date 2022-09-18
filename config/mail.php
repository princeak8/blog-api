<?php

$mailConfig = [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | This option controls the default mailer that is used to send any email
    | messages sent by your application. Alternative mailers may be setup
    | and used as needed; however, this mailer will be used by default.
    |
    */

    'default' => env('MAIL_MAILER', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the mailers used by your application plus
    | their respective settings. Several examples have been configured for
    | you and you are free to add your own as your application requires.
    |
    | Laravel supports a variety of mail "transport" drivers to be used while
    | sending an e-mail. You will specify which one you are using for your
    | mailers below. You are free to add additional mailers as required.
    |
    | Supported: "smtp", "sendmail", "mailgun", "ses",
    |            "postmark", "log", "array", "failover"
    |
    */

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
            'port' => env('MAIL_PORT', 587),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'auth_mode' => null,
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'mailgun' => [
            'transport' => 'mailgun',
        ],

        'postmark' => [
            'transport' => 'postmark',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -t -i'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all e-mails sent by your application to be sent from
    | the same address. Here, you may specify a name and address that is
    | used globally for all e-mails that are sent by your application.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Markdown Mail Settings
    |--------------------------------------------------------------------------
    |
    | If you are using Markdown based email rendering, you may configure your
    | theme and component paths here, allowing you to customize the design
    | of the emails. Or, you may simply stick with the Laravel defaults!
    |
    */

    'markdown' => [
        'theme' => 'default',

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];

function add_new_smtp_mailer($mailConfig, $mailer, $host, $username, $password, $port) {
    $mailConfig['mailers'][$mailer] =  $mailConfig['mailers']['smtp'];
    $mailConfig['mailers'][$mailer]['host'] = $host;
    $mailConfig['mailers'][$mailer]['username'] = $username;
    $mailConfig['mailers'][$mailer]['password'] = $password;
    $mailConfig['mailers'][$mailer]['port'] = $port;
    return $mailConfig;
} 

$newMailers = [
    ["name"=>"nnedi", "host"=>env('nnedi_MAIL_HOST'), "username"=>env('nnedi_MAIL_USERNAME'), "password"=>env('nnedi_MAIL_PASSWORD'), "port"=>env('blog_MAIL_PORT')],
    ["name"=>"blog", "host"=>env('blog_MAIL_HOST'), "username"=>env('blog_MAIL_USERNAME'), "password"=>env('blog_MAIL_PASSWORD'), "port"=>env('blog_MAIL_PORT')],
    ["name"=>"princeak", "host"=>env('princeak_MAIL_HOST'), "username"=>env('princeak_MAIL_USERNAME'), "password"=>env('princeak_MAIL_PASSWORD'), "port"=>env('blog_MAIL_PORT')]
];

if(count($newMailers) > 0) {
    foreach($newMailers as $mailer) {
        $mailConfig = add_new_smtp_mailer($mailConfig, $mailer['name'], $mailer['host'], $mailer['username'], $mailer['password'], $mailer['port']);
    }
}

return $mailConfig;
