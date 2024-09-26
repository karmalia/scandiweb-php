<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit39489e2f9e0d206fce44223ccd5a8b00
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit39489e2f9e0d206fce44223ccd5a8b00::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit39489e2f9e0d206fce44223ccd5a8b00::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit39489e2f9e0d206fce44223ccd5a8b00::$classMap;

        }, null, ClassLoader::class);
    }
}
