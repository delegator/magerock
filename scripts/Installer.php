<?php
class Installer
{
    const SUCCESS = 2;
    const INFO    = 4;
    const WARNING = 3;
    const DANGER  = 1;

    public static function loadEnv($spacers = 0)
    {
        require __DIR__ . '/../vendor/autoload.php';
        $spacer = '';
        for ($i=0;$i<$spacers;$i++) { $spacer .= ' '; }
        self::output($spacer . "├─ Loading .env");
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
            'SECRET_KEY',
            'ADMIN_FNAME',
            'ADMIN_LNAME',
            'ADMIN_EMAIL',
            'ADMIN_USER',
            'ADMIN_PASSWORD'
            ));
        } catch (RuntimeException $e) {
            self::output($e->getMessage(), Installer::DANGER);
            exit;
        }
        self::output($spacer . "│  └─ Successfully loaded .env", Installer::SUCCESS);
    }
    public static function command($cmd, $verbose=false)
    {
        if ($verbose) {
            self::output("Running `$cmd`", Installer::WARNING);
        }
        exec('pushd web/magento && ' . $cmd . ' && popd',$output,$error);
        if ($verbose) {
            foreach($output as $o) {
                self::output(">> $o", Installer::WARNING);    
            }
        }
        return !$error; //return whether successful or not
    }
    public static function generateLocal()
    {   
        self::output('Regenerating local.xml');
        self::loadEnv(2);
        self::output('  ├─ checking for app/etc/local.xml');
        $cmd1 = '[ -f app/etc/local.xml ] || exit 1';
        $cmd2 = 'mv app/etc/local.xml app/etc/local.backup';
        $cmd3 = getenv('MAGERUN')
            . ' ' . 'local-config:generate' 
            . ' ' . getenv('DB_HOST') 
            . ' ' . getenv('DB_USER')
            . ' ' . getenv('DB_PASSWORD')
            . ' ' . getenv('DB_NAME')
            . ' ' . 'files' 
            . ' ' . 'admin'
            . ' ' . getenv('SECRET_KEY');
        $cmd4 = 'rm app/etc/local.backup';
        if (self::command($cmd1)) {
            self::command($cmd2);
            self::output('  ├─ backed up old local.xml', Installer::SUCCESS);
            self::command($cmd3);
            self::output('  ├─ generated new local.xml', Installer::SUCCESS);
            self::command($cmd4);
            self::output('  └─ replaced old local.xml', Installer::SUCCESS);
            self::output("Please not that this only created a local.xml file; it did not fill the database with default values.");
        } else {
            self::output("  │  └─ No local.xml found", Installer::WARNING);
            self::output("  └─ Installing Magento based on .env");
            self::install();
        }
    }

    public static function install() 
    {
        self::loadEnv(5);

        self::output("     └─ Installing");
        $cmd = 'php -f install.php -- '
            . '--license_agreement_accepted "yes" '
            . '--locale "'. getenv('LOCALE') . '" '
            . '--timezone "'. getenv('TIME_ZONE') . '" '
            . '--default_currency "'. getenv('CURRENCY') . '" '
            . '--db_host "'. getenv('DB_HOST') . '" '
            . '--db_name "'. getenv('DB_NAME') . '" '
            . '--db_user "'. getenv('DB_USER') . '" '
            . '--db_pass "'. getenv('DB_PASSWORD') . '" ' 
            . '--db_prefix "" ' 
            . '--session_save "files" ' 
            . '--admin_frontname "admin" ' 
            . '--url "'. getenv('BASE_URL') . '" ' 
            . '--use_rewrites "yes" ' 
            . '--use_secure "yes" ' 
            . '--secure_base_url "'. getenv('BASE_URL') . '" '
            . '--use_secure_admin "yes" '
            . '--admin_firstname "' . getenv('ADMIN_FNAME') . '" '
            . '--admin_lastname "' . getenv('ADMIN_LNAME') . '" '
            . '--admin_email "' . getenv('ADMIN_EMAIL') . '" '
            . '--admin_username "' . getenv('ADMIN_USER') . '" '
            . '--admin_password "' . getenv('ADMIN_PASSWORD') . '" '
            . '--encryption_key "'. getenv('SECRET_KEY') . '" ';

            if (self::command($cmd . ' 2>&1', true)) {
                self::output("        └─ Success!");
            } else {
                self::output("        └─ Something went wrong", Installer::DANGER);
            }
    }

    public static function modman()
    {
        self::output('Initializing Modman');
        self::loadEnv(2);
        $cmd1 = '[ -f .modman ] || exit 1';
        if (!self::command($cmd1)) {
            self::output('  └─ Modman already initialized', Installer::WARNING);
        } else {
            self::output('  ├─ modman init', Installer::INFO);
            self::command('modman init');
            self::output('  └─ modman link', Installer::INFO);
            self::command('modman link ../theme');
        }
    }

    public static function output($string, $color = Installer::INFO, $newline = true)
    {
        //passthru("printf $(tput setaf " . Installer::INFO . ")");
        echo ":: ";
        //passthru("printf $(tput setaf " . $color . ")");
        echo $string;
        //passthru("printf $(tput sgr0)");
        if ($newline) {
            echo "\n";
        }
    }
}
