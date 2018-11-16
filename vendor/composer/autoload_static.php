<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitced7dd9d55474021911ab01c2d4ad7e8
{
    public static $prefixLengthsPsr4 = array (
        'd' => 
        array (
            'dodgepudding\\wechat\\sdk\\' => 24,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'dodgepudding\\wechat\\sdk\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitced7dd9d55474021911ab01c2d4ad7e8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitced7dd9d55474021911ab01c2d4ad7e8::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}