<?php

namespace Deployer;

use Symfony\Component\Console\Input\InputOption;

require 'recipe/symfony4.php';

option('all-fixtures', 'a', InputOption::VALUE_NONE, 'Load all fixtures');

// @todo configure this
// project repository
// set('repository', 'https://github.com/foo/bar.git');

// project repository
set('repository', 'https://github.com/jibundeyare/src-symfony-3.4.git');

// hosts

// @todo configure this
// host('example.com')
//     ->stage('prod')
//     ->user(getenv('ssh_user'))
//     // user the web server runs as. If this parameter is not configured, deployer try to detect it from the process list.
//     ->set('http_user', getenv('ssh_user'))
//     // projects directory
//     ->set('projects_dir', 'projects')
//     // projects name
//     ->set('application', 'foo')
//     ->set('deploy_path', '~/{{projects_dir}}/{{application}}');

// @todo configure this
// host('test.example.com')
//     ->stage('test')
//     ->user(getenv('ssh_user'))
//     // user the web server runs as. If this parameter is not configured, deployer try to detect it from the process list.
//     ->set('http_user', getenv('ssh_user'))
//     // projects directory
//     ->set('projects_dir', 'projects')
//     // projects name
//     ->set('application', 'foo')
//     ->set('deploy_path', '~/{{projects_dir}}/{{application}}');

host('popschool-lens.fr')
    ->stage('prod')
    ->user(getenv('ssh_user'))
    // user the web server runs as. If this parameter is not configured, deployer try to detect it from the process list.
    ->set('http_user', getenv('ssh_user'))
    // projects directory
    ->set('projects_dir', 'projects')
    // projects name
    ->set('application', 'src-symfony-3.4')
    ->set('deploy_path', '~/{{projects_dir}}/{{application}}');

host('srv431')
    ->stage('test')
    ->user(getenv('ssh_user'))
    // user the web server runs as. If this parameter is not configured, deployer try to detect it from the process list.
    ->set('http_user', getenv('ssh_user'))
    // projects directory
    ->set('projects_dir', 'projects')
    // projects name
    ->set('application', 'src-symfony-3.4')
    ->set('deploy_path', '~/{{projects_dir}}/{{application}}');

// set default stage
set('default_stage', 'prod');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// shared files / dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// writable dirs by web server
add('writable_dirs', []);
set('allow_anonymous_stats', false);

// writable mode
//
// - acl (default) use setfacl for changing ACL of dirs.
// - chmod use unix chmod command,
// - chown use unix chown command,
// - chgrp use unix chgrp command,
set('writable_mode', 'chmod');

// whether to use sudo with writable command. Default to false.
set('writable_use_sudo', false);

// tasks

// [optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// migrate database before symlink new release.
before('deploy:symlink', 'database:migrate');

desc('Test deployer');
task('test:hello', function () {
    writeln('Hello world');
});

desc('Get server hostname');
task('test:hostname', function () {
    $result = run('cat /etc/hostname');
    writeln("$result");
});

desc('Copy .env.{{stage}}.local as .env.local');
task('deploy:env', function () {
    upload('.env.{{stage}}.local', '~/{{projects_dir}}/{{application}}/shared/.env.local');
});

desc('Clean git files');
task('clean:git-files', function () {
    run('rm -fr ~/{{projects_dir}}/{{application}}/current/.git');
});

desc('Load fixtures');
task('fixtures:load', function () {
    $allFixtures = null;

    if (input()->hasOption('all-fixtures')) {
        $allFixtures = input()->getOption('all-fixtures');
    }

    if ($allFixtures) {
        if (get('stage') == 'prod') {
            writeln("Loading all fixtures");
            $result = run('{{bin/console}} doctrine:fixtures:load --no-interaction --append');
            writeln("$result");
        } else { // get('stage') != 'prod'
            writeln("Loading all fixtures");
            $result = run('{{bin/console}} doctrine:fixtures:load --no-interaction --purge-with-truncate');
            writeln("$result");
        }
    } else {
        if (get('stage') == 'prod') {
            writeln("Loading required fixtures");
            $result = run('{{bin/console}} doctrine:fixtures:load --no-interaction --group=required --append');
            writeln("$result");
        } else { // get('stage') != 'prod'
            writeln("Loading test fixtures");
            $result = run('{{bin/console}} doctrine:fixtures:load --no-interaction --group=test --purge-with-truncate');
            writeln("$result");
        }
    }
});

desc('Rollback database');
task('database:rollback', function () {
    $options = '--allow-no-migration';
    if (get('migrations_config') !== '') {
        $options = sprintf('%s --configuration={{release_path}}/{{migrations_config}}', $options);
    }
    run(sprintf('{{bin/console}} doctrine:migrations:migrate prev %s', $options));
});

after('deploy', 'clean:git-files');

