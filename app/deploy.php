<?php
namespace Deployer;

require 'recipe/symfony.php';

// Project name
set('application', 'poketournament');

// Project repository
set('repository', 'git@github.com:nayodahl/poketournament.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

set('allow_anonymous_stats', false);
set('composer_options', '{{composer_action}} --verbose --no-progress --no-interaction --no-dev --optimize-autoloader');

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);

// Hosts
host('nayo.kernl.fr')
    ->set('deploy_path', '~/www')
    ->user('poke')
    ->set('bin/console', function () {
        return parse('{{release_path}}/app/bin/console');
    })
    ->set('shared_files', [
        'app/.env'
    ])
    ->set('shared_dirs', [
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


// Tasks

task('deploy:vendors', function () {
    foreach (get('vendors_tasks', []) as $task) {
        run($task);
    }
});

task('deploy:build', function () {
    foreach (get('build_tasks', []) as $task) {
        run($task);
    }
});

task('deploy:restart', function () {
    foreach (get('restart_tasks', []) as $task) {
        run($task);
    }
});

task('deploy:after', function () {
    foreach (get('after_tasks', []) as $task) {
        run($task);
    }
});

task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:build',
    'deploy:symlink',
    'deploy:after',
    'deploy:unlock',
    'deploy:restart',
    'cleanup',
]);

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');

