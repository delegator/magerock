server 'localhost', user: 'wlewis', roles: %w{app db web}

# Set rails environment
set :mage_env, 'staging'

# Destination directory
set :deploy_to, '/users/wlewis/Sites/test.magerock'

SSHKit.config.command_map[:php] = "/usr/local/bin/php"
SSHKit.config.command_map[:composer] = "/usr/local/bin/composer"

set :composer_install_flags, '--no-dev --no-interaction --optimize-autoloader'

set :keep_releases, 1