<?php
namespace Deployer;

require 'recipe/laravel.php';
require 'recipe/npm.php';

// Project name
set('application', 'devex-test');

// Project repository
set('repository', 'git@github.com:eugene-khorev/devex.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);
set('allow_anonymous_stats', false);

// Hosts
host('devex.risk.si')
    ->user('root')
    ->set('deploy_path', '/var/www/{{application}}');    
    
// Tasks
task('build', function () {
    run('cd {{release_path}} && npm run prod');
});

// Install npm modules
after('deploy:update_code', 'npm:install');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

