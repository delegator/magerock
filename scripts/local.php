<?php

require_once(
    __DIR__ 
    . DIRECTORY_SEPARATOR . '..' 
    . DIRECTORY_SEPARATOR . 'vendor' 
    . DIRECTORY_SEPARATOR . 'autoload.php'
    );

class LocalWriter
{
    const SUCCESS = 2;
    const INFO    = 4;
    const WARNING = 3;
    const DANGER  = 1;
    public function run()
    {
        $this->output("Loading .env");
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
            $this->output($e->getMessage(), LocalWriter::DANGER);
            exit;
        }
        $this->output("Successfully loaded .env", LocalWriter::SUCCESS);
        $cmd = 'pushd web/magento &&'
            . ' ' . 'mv app/etc/local.xml app/etc/local.backup &&'
            . ' ' . getenv('MAGERUN')
            . ' ' . 'local-config:generate' 
            . ' ' . getenv('DB_HOST') 
            . ' ' . getenv('DB_USER')
            . ' ' . getenv('DB_PASSWORD')
            . ' ' . getenv('DB_NAME')
            . ' ' . 'files' 
            . ' ' . 'admin'
            . ' ' . getenv('SECRET_KEY') . ' &&'
            . ' ' . 'rm app/etc/local.backup';
        $this->output("Running `$cmd`", LocalWriter::WARNING);
        exec($cmd);
        $this->output("Finished",LocalWriter::SUCCESS);
        $this->output("Note: this only created a local.xml file, it did not fill the database with default values.");
    }

    public function output($string, $color = LocalWriter::INFO)
    {

        passthru("printf $(tput setaf " . $color . ")");
        echo ":: " . $string;
        passthru("printf $(tput sgr0)");
        echo "\n";
    }
}


(new LocalWriter())->run();
echo "\n";