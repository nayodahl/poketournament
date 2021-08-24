<?php
namespace Deployer;

require 'recipe/symfony.php';

// Project name
set('application', 'poketournament');

// Project repository
set('repository', 'git@github.com:nayodahl/poketournament.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

set('allow_anonymous_stats', false);
set('composer_options', '{{composer_action}} --verbose --no-progress --no-interaction --no-dev --optimize-autoloader');

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);


// Hosts
host('nayo.kernl.fr')
    ->set('deploy_path', '~/{{application}}');    
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

task('deploy', function () {
    $result = run('pwd');
    writeln("Current dir: $result");
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');

