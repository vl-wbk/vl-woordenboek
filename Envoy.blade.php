@setup

$branch = 'develop';
$server = 'chimpy.be -p 26';
$applicationDir = 'domains/vl-woordenboek';
$userAndServer = 'mg119947@'. $server;

function logMessage($message) {
return "echo '\033[32m" .$message. "\033[0m';\n";
}
@endsetup

@servers(['local' => '127.0.0.1', 'remote' => $userAndServer])

@task('deploy', ['on' => 'remote'])
cd {{ $applicationDir }}
{{ logMessage("INFO  Put the application in maintenance mode...") }}
php artisan down --refresh=30 --with-secret
{{ logMessage("INFO  Preventive MySQL back-up before the deployment ...") }}
php artisan backup:run
{{ logMessage("INFO  Pull the latest changes from the repository...") }}
git add .
git stash
git pull origin {{ $branch }}
{{ logMessage("INFO  Install the latest dependencies...") }}
composer update --optimize-autoloader
{{ logMessage("INFO  Run the migrations...") }}
php artisan migrate --force
{{ logMessage("INFO  Optimize the application...") }}
php artisan optimize
{{ logMessage("INFO  Bring the application back online") }}
php artisan up
@endtask

@task('backup:database', ['on' => 'remote'])
{{ logMessage("Backing up database...") }}
{{ logMessage("-----") }}

cd {{ $applicationDir }}
php artisan backup:run

{{ logMessage("-----") }}
@endtask

@task('maintenance:start', ['on' => 'remote'])
{{ logMessage("Putting the application in maintenance mode...") }}
{{ logMessage("-----") }}

cd {{ $applicationDir }}
php artisan down --refresh=30 --with-secret
{{ logMessage("-----") }}
@endtask

@task('maintenance:stop', ['on' => 'remote'])
{{ logMessage("Bringing the application out of maintenance mode...") }}
{{ logMessage("-----") }}
cd {{ $applicationDir }}
php artisan up
{{ logMessage("-----") }}
@endtask

