<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1e962b6eabc0fea206ed15a8453bc209
{
    public static $prefixesPsr0 = array (
        'S' => 
        array (
            'Slim' => 
            array (
                0 => __DIR__ . '/..' . '/slim/slim',
            ),
        ),
    );

    public static $classMap = array (
        'PiramideUploader' => __DIR__ . '/../..' . '/piramide-uploader/PiramideUploader.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit1e962b6eabc0fea206ed15a8453bc209::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit1e962b6eabc0fea206ed15a8453bc209::$classMap;

        }, null, ClassLoader::class);
    }
}
