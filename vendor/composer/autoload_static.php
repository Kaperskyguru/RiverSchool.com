<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5d92b837d8033d7c1032e193b843ddf4
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5d92b837d8033d7c1032e193b843ddf4::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5d92b837d8033d7c1032e193b843ddf4::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}