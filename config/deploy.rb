# config valid only for current version of Capistrano
lock '3.4.0'

set :application, 'magerock'
set :repo_url, 'git@github.com:delegator/magerock.git'

set :branch, `git rev-parse --abbrev-ref HEAD`.chomp

set :log_level, :debug

set :linked_files, fetch(:linked_files, []).push('.env')
set :linked_dirs, fetch(:linked_dirs, []).push('web/magento/media', 'web/magento/var/log', 'web/magento/var/cache')

namespace :mage do

  desc "Initialize Magento Linked Files/Directories"
  task :init do
    on roles(:all) do |host|
      info :deploy_to
      execute 'mkdir -p shared/web/magento'
      execute 'mkdir -p shared/web/magento/log'
      execute 'mkdir -p shared/web/magento/var'
      execute 'mkdir -p shared/web/magento/media'
      execute 'mkdir -p shared/web/magento/app/etc'
      info 'Default shared directories created'
    end
  end

  desc "Test"
  task :test do
    on roles(:all) do |host|
      execute "#{deploy_to}/current/scripts/run-modman.sh #{SSHKit.config.command_map[:modman]}"
    end
  end

end
