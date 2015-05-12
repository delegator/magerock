# config valid only for current version of Capistrano
lock '3.4.0'

set :application, 'magerock'
set :repo_url, 'git@github.com:delegator/magerock.git'

set :log_level, :debug

set :linked_files, fetch(:linked_files, []).push('app/etc/local.xml', '.env')
set :linked_dirs, fetch(:linked_dirs, []).push('media', 'var/log', 'var/cache')

SSHKit.config.command_map[:composer] = "/usr/local/bin/composer"

namespace :deploy do

  after :restart, :clear_cache do
    on roles(:web), in: :groups, limit: 3, wait: 10 do

    end
  end

end
