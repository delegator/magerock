server 'localhost', user: 'wlewis', roles: %w{app db web}

# Set rails environment
set :mage_env, 'staging'

# Destination directory
set :deploy_to, '/users/wlewis/Sites/test.magerock'

set :branch, fetch(:branch, "testing")

set :composer_install_flags, '--no-dev --no-interaction --optimize-autoloader'

set :keep_releases, 1