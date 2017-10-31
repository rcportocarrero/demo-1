<?php

// All Deployer recipes are based on `recipe/common.php`.
# require 'recipe/common.php';
require 'recipe/zend_framework.php';

define('APP_DIR', '/var/www/html/died/died_base_app');
define('SERVER', 'diedds.com');
define('PORT', '22');
define('USER', 'www-data');
define('PASS', 'mayolo777');
define('APP_REPO', 'git@git.diedds.com:sing88/died_base_app.git');

server('prod', SERVER, PORT)
        ->user(USER)
        ->password(PASS)
        ->stage('devel')
        ->env('deploy_path', APP_DIR); // Define the base path to deploy your project to.
// Specify the repository from which to download your project's code.
// The server needs to have git installed for this to work.
// If you're not using a forward agent, then the server has to be able to clone
// your project from this repository.

set('repository', APP_REPO);


task('reload:composer-update', function () {
//    run('chown ' . USER . ':' . USER . '  ' . APP_DIR . ' -R');
//    run('php '.APP_DIR.'/current/public/index.php development enable');
    run('cd ' . APP_DIR . '/current/ && composer update');
})->desc('Desplegando');

/*
  run('cd /var/www/html/App1_tmp');
  run('chown www-data:www-data ** -R');
  run('cd /var/www/html/App1_tmp/current/');

  run('php public/index.php development enable');
 */
after('deploy', 'reload:composer-update');
// php public/index.php development enable



