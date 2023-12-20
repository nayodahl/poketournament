<?php
namespace Deployer;

require 'recipe/symfony.php';

// Config

set('repository', 'git@github.com:nayodahl/poketournament.git');
set('application', 'poketournament');
set('composer_options', '{{composer_action}} --verbose --no-progress --no-interaction --no-dev --optimize-autoloader');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('nayo.kernl.fr')
    ->set('remote_user', 'poke')
    ->set('deploy_path', '~/www')
    ->set('keep_releases', 5)
    ->set('http_user', 'poke')
    ->set('http_group', 'poke')
    ->set('bin/console', function () {
        return parse('{{release_path}}/app/bin/console');
    })
    ->set('shared_files', [
        'app/.env'
    ])
    ->set('shared_dirs', [
        'app/var/log',
    ])
    ->set('writable_dirs', [
        'app/var/log',
    ])
    ->set('vendors_tasks', [
        'cd {{release_path}}/app && {{bin/composer}} {{composer_options}}',
        'cd {{release_path}}/app && yarn install --silent --no-progress',
    ])
    ->set('build_tasks', [
        'cd {{release_path}}/app && yarn encore production',
    ])
    ->set('restart_tasks', [
        'sudo /etc/init.d/php8.1-fpm restart',
    ])
;


// Hooks

after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.
before('deploy:symlink', 'database:migrate');