<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb969db4c8a173da28a5ef188480eef80
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Workerman\\' => 10,
        ),
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Workerman\\' => 
        array (
            0 => __DIR__ . '/..' . '/workerman/workerman',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'S' => 
        array (
            'Symfony\\Component\\Routing' => 
            array (
                0 => __DIR__ . '/..' . '/symfony/routing',
            ),
            'Symfony\\Component\\HttpFoundation' => 
            array (
                0 => __DIR__ . '/..' . '/symfony/http-foundation',
            ),
            'Symfony\\Component\\ClassLoader' => 
            array (
                0 => __DIR__ . '/..' . '/symfony/class-loader',
            ),
            'SessionHandlerInterface' => 
            array (
                0 => __DIR__ . '/..' . '/symfony/http-foundation/Symfony/Component/HttpFoundation/Resources/stubs',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb969db4c8a173da28a5ef188480eef80::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb969db4c8a173da28a5ef188480eef80::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitb969db4c8a173da28a5ef188480eef80::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
