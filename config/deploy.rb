# config valid only for current version of Capistrano
lock '3.4.0'

set :application, 'magerock'
set :repo_url, 'git@github.com:delegator/magerock.git'

set :branch, `git rev-parse --abbrev-ref HEAD`.chomp

set :log_level, :info

set :linked_files, fetch(:linked_files, []).push(
  '.env',
)
set :linked_dirs, fetch(:linked_dirs, []).push(
  'web/media/catalog',
  'web/media/tmp',
  'web/media/import',
  'web/var/log',
  'web/var/cache'
)
set :grunt_target_path, -> { release_path.join('') } # Grunt

namespace :mage do

  desc "Initialize Magento Linked Files/Directories"
  task :init do
    on roles(:all) do |host|
      info :deploy_to
      execute "mkdir -p #{deploy_to}/shared/web"
      execute "mkdir -p #{deploy_to}/shared/web/var/log"
      execute "mkdir -p #{deploy_to}/shared/web/var/cache"
      execute "mkdir -p #{deploy_to}/shared/web/media/catalog"
      execute "mkdir -p #{deploy_to}/shared/web/media/tmp"
      execute "mkdir -p #{deploy_to}/shared/web/media/import"
      info 'Default shared directories created'
    end
  end

  desc "Install Magento"
  task :install do
    on roles(:all) do |host|
      info 'Installing Magento using .env file for settings'
      execute "#{deploy_to}/current/scripts/install-magento.sh #{SSHKit.config.command_map[:php]}"
    end
  end

  desc "Deploy Modman"
  task :modman do
    on roles(:all) do |host|
      execute "#{deploy_to}/current/scripts/run-modman.sh #{SSHKit.config.command_map[:modman]} #{SSHKit.config.command_map[:modman_gen]}"
    end
  end

  desc "Generate local.xml"
  task :localxml do
    on roles(:all) do |host|
      info 'Generating local.xml based on .env. Will overwrite current local.xml if there is one.'
      execute "#{deploy_to}/current/scripts/generate-local.sh #{SSHKit.config.command_map[:magerun]}"
    end
  end

  desc "Generate products"
  task :prodgen do
    on roles(:all) do |host|
      info 'Generating products'
      execute "#{SSHKit.config.command_map[:php]} -f #{deploy_to}/current/web/shell/generate.php -- -n 50"
    end
  end

end
before 'deploy:updated', 'grunt'
after :deploy, 'mage:modman'
after :deploy, 'mage:localxml'
