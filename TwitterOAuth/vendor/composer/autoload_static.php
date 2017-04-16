<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit42a9165139f1f5451d04434f3096bcda
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'TwitterOAuth\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'TwitterOAuth\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit42a9165139f1f5451d04434f3096bcda::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit42a9165139f1f5451d04434f3096bcda::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
