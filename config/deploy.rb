# config valid only for current version of Capistrano
lock '3.4.0'

set :application, 'magerock'
set :repo_url, 'git@github.com:delegator/magerock.git'

set :branch, `git rev-parse --abbrev-ref HEAD`.chomp

set :log_level, :info

set :linked_files, fetch(:linked_files, []).push(
  '.env',
  'web/app/etc/local.xml'
)
set :linked_dirs, fetch(:linked_dirs, []).push(
  'web/media',
  'web/var'
)

namespace :mage do
  desc "Initialize Magento Linked Files/Directories"
  task :init do
    on roles(:all) do |host|
      info :deploy_to
      execute "mkdir -p #{deploy_to}/shared/web/var/cache"
      execute "mkdir -p #{deploy_to}/shared/web/var/log"
      execute "mkdir -p #{deploy_to}/shared/web/var/package"
      execute "mkdir -p #{deploy_to}/shared/web/var/report"
      execute "mkdir -p #{deploy_to}/shared/web/var/session"
      execute "mkdir -p #{deploy_to}/shared/web/media"
      info 'Default shared directories created'
    end
  end
end
