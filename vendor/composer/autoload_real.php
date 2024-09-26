<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit39489e2f9e0d206fce44223ccd5a8b00
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit39489e2f9e0d206fce44223ccd5a8b00', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit39489e2f9e0d206fce44223ccd5a8b00', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit39489e2f9e0d206fce44223ccd5a8b00::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
