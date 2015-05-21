# config valid only for current version of Capistrano
lock '3.4.0'

set :application, 'magerock'
set :repo_url, 'git@github.com:delegator/magerock.git'

set :branch, `git rev-parse --abbrev-ref HEAD`.chomp

set :log_level, :info

set :linked_files, fetch(:linked_files, []).push('.env')
set :linked_dirs, fetch(:linked_dirs, []).push('web/magento/media', 'web/magento/var/log', 'web/magento/var/cache')

namespace :mage do

  desc "Initialize Magento Linked Files/Directories"
  task :init do
    on roles(:all) do |host|
      info :deploy_to
      execute "mkdir -p #{deploy_to}/shared/web/magento"
      execute "mkdir -p #{deploy_to}/shared/web/magento/log"
      execute "mkdir -p #{deploy_to}/shared/web/magento/var"
      execute "mkdir -p #{deploy_to}/shared/web/magento/media"
      execute "mkdir -p #{deploy_to}/shared/web/magento/app/etc"
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

end
