<?php
class Installer
{
    const SUCCESS = 2;
    const INFO    = 4;
    const WARNING = 3;
    const DANGER  = 1;

    public static function loadEnv()
    {
        require __DIR__ . '/../vendor/autoload.php';
        self::output("Loading .env");
        Dotenv::load(
            __DIR__
            . DIRECTORY_SEPARATOR . '..'
            );

        try {
        Dotenv::required(array(
            'LOCALE',
            'TIME_ZONE',
            'CURRENCY',
            'DB_NAME',
            'DB_USER',
            'DB_PASSWORD',
            'DB_HOST',
            'BASE_URL',
            'MAGERUN',
            'SECRET_KEY'
            ));
        } catch (RuntimeException $e) {
            self::output($e->getMessage(), Installer::DANGER);
            exit;
        }
        self::output("Successfully loaded .env", Installer::SUCCESS);
    }
    public static function command($cmd)
    {
        self::output("Running `$cmd`", Installer::WARNING);
        exec($cmd,$output,$success);
        if (!$success) {
            self::output("Finished", Installer::SUCCESS);
        } else {
            self::output("Encountered Errors", Installer::DANGER);
        }
        foreach ($output as $o)
        {
            //echo $o;
            //echo "\n";
        }
        //echo "\n";
        return $success;
    }
    public static function generateLocal()
    {
        self::loadEnv();
        $cmd1 = 'pushd web/magento && mv app/etc/local.xml app/etc/local.backup && popd';
        $cmd2 = 'pushd web/magento &&'
            . ' ' . getenv('MAGERUN')
            . ' ' . 'local-config:generate' 
            . ' ' . getenv('DB_HOST') 
            . ' ' . getenv('DB_USER')
            . ' ' . getenv('DB_PASSWORD')
            . ' ' . getenv('DB_NAME')
            . ' ' . 'files' 
            . ' ' . 'admin'
            . ' ' . getenv('SECRET_KEY')
            . ' && popd';
        $cmd3 = 'pushd web/magento && rm app/etc/local.backup';
        if (!self::command($cmd1)) {
            self::command($cmd2);
            self::command($cmd3);
            self::output("Please not that this only created a local.xml file; it did not fill the database with default values.");
        } else {
            self::output("Installing...");
            self::install();
        }
    }

    public static function install() 
    {
        self::loadEnv();

        $cmd = 'pushd web/magento && php -f install.php -- ' . 
            '--license_agreement_accepted "yes" ' . 
            '--locale "'. getenv('LOCALE') . '" ' . 
            '--timezone "'. getenv('TIME_ZONE') . '" ' . 
            '--default_currency "'. getenv('CURRENCY') . '" ' . 
            '--db_host "'. getenv('DB_HOST') . '" ' . 
            '--db_name "'. getenv('DB_NAME') . '" ' . 
            '--db_user "'. getenv('DB_USER') . '" ' . 
            '--db_pass "'. getenv('DB_PASSWORD') . '" ' . 
            '--db_prefix "" ' . 
            '--session_save "files" ' . 
            '--admin_frontname "admin" ' . 
            '--url "'. getenv('BASE_URL') . '" ' . 
            '--use_rewrites "yes" ' . 
            '--use_secure "yes" ' . 
            '--secure_base_url "'. getenv('BASE_URL') . '" ' . 
            '--use_secure_admin "yes" ' . 
            '--admin_firstname "John" ' . 
            '--admin_lastname "Smith" ' . 
            '--admin_email "john.smith@example.com" ' . 
            '--admin_username "admin" ' . 
            '--admin_password "password123" ' . 
            '--encryption_key "'. getenv('SECRET_KEY') . '" ' .
            ' && popd';

            self::command($cmd);
    }

    public static function output($string, $color = Installer::INFO)
    {

        passthru("printf $(tput setaf " . $color . ")");
        echo ":: " . $string;
        passthru("printf $(tput sgr0)");
        echo "\n";
    }
}
