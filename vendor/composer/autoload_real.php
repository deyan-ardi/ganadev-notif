<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit388f6ee950e5151e9e5c871f6c7ad9a5
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

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit388f6ee950e5151e9e5c871f6c7ad9a5', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit388f6ee950e5151e9e5c871f6c7ad9a5', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit388f6ee950e5151e9e5c871f6c7ad9a5::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
