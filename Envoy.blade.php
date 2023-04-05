{{-- Configuration section --}}
@setup
    $__container->servers([
        'local' => '127.0.0.1',
        'tkg-production' => 'crm@51.38.54.12 -p 2232',
    ]);

    /* Git config */
    $repo = 'ssh://git@git.cnsi-sd.net:2222/RoyalPriceTeam/crm.git';
    $branch = isset($branch) ? $branch : "master";
    $on = isset($on) ? $on : "local";

    /* Servers configuration */
    switch($on) {
        case 'tkg-production':
            $php = "php8.2";
            $app_dir = '/var/www/html/crm';
            break;
        case 'local':
        default:
            $php = "php8.2";
            $app_dir = '/var/www/html/crm';
            break;
    }

    /* Releases paths */
    $current_dir  =  $app_dir . '/current';
    $releases_dir =  $app_dir . '/releases';
    $release_dir  =  $app_dir . '/releases/' . date('YmdHis');
    $shared_dir   =  $app_dir . '/shared';
    $composer_phar = $release_dir . '/composer.phar';

    /* Number of releases to keep */
    $keep = 10;

    /* Sharable resources */
    $shared = [
        'storage' => 'd',
        'bootstrap/cache' => 'd',
        '.env' => 'f',
    ];

    /* Scripts PHP à éxécuter */
    $php_scripts = [
        'artisan migrate --force',
        'artisan db:seed --force',
        'artisan cache:clear',
        'artisan route:clear',
        'artisan config:clear',
        'artisan view:clear',
    ];

    /* Dossier de documentation à build */
    $mkdocs = [
        'doc/user-doc/',
        'doc/admin/',
    ];

    /* Sentinel scripts */
    $sentinel_scripts_dir = '/home/scripts';
@endsetup

{{-- Deployment macro, use to deploy a new version of a existent project --}}
@macro('app:deploy', ['on' => $on])
    clone
    symlinks:shared
    framework_folders:create
    composer:install
    hack:monolog
    npm:build
    php:execute_scripts
    mkdocs:build
    symlinks:current
    queue:restart
    clean:releases
{{--    integrity:reload--}}
@endmacro

{{-- Reloads integrity testers --}}
@task('integrity:reload', ['on' => $on])
    {{ $sentinel_scripts_dir }}/scripts/current/bin/integrity/md5dir.sh {{ $release_dir }};
@endtask

{{-- Clone task, creates release directory, then clones into it --}}
@task('clone', ['on' => $on])
    eval "$(ssh-agent -s)";
    echo "Cloning repository";

    [ -d {{ $releases_dir  }} ] || mkdir -p {{ $releases_dir }};
    git clone {{ $repo }}  --branch={{ $branch }} {{ $release_dir }} --quiet --recurse-submodules;

    echo "Repository has been cloned";
@endtask

{{-- Create some laravel required folders (storage/framework) --}}
@task('framework_folders:create', ['on' => $on])
    echo "Creating some laravel required folders";
    mkdir -p {{ $shared_dir }}/storage/framework/
    cd {{ $shared_dir }}/storage/framework/
    mkdir -p sessions
    mkdir -p views
    mkdir -p cache
@endtask

{{-- Build JS / CSS --}}
@task('npm:build', ['on' => $on])
    echo "Building CSS & JS files";

    cd {{ $release_dir }};
    npm install --quiet;
    npm run --silent build;

    echo "CSS & JS files have been builded";
@endtask

{{-- Install composer dependencies --}}
@task('composer:install', ['on' => $on])
    echo "Clear composer cache"
    {{ $php }} {{ $composer_phar }} clear-cache

    {{-- Global composer dependencies --}}
    echo "Installing global composer dependencies";
    cd {{ $release_dir }};
    {{ $php }} {{ $composer_phar }} install --no-interaction --no-dev --quiet --optimize-autoloader;

    echo "Composer dependencies have been installed";
@endtask

{{-- Execute some PHP scripts --}}
@task('php:execute_scripts', ['on' => $on])
    cd {{ $release_dir }};
    @foreach($php_scripts as $php_script)
        echo "Executing {{ $php_script }}";
        {{ $php }} {{ $php_script }};
    @endforeach
@endtask

@task('hack:monolog', ['on' => $on])
    cd {{ $release_dir }};
    sed -i -e \
        "s/stream_set_chunk_size(\$stream, [[:graph:]]\{23\}/stream_set_chunk_size(\$stream, static::DEFAULT_CHUNK_SIZE)/g" \
        vendor/monolog/monolog/src/Monolog/Handler/StreamHandler.php;
@endtask

{{-- Clean old releases --}}
@task('clean:releases', ['on' => $on])
    echo "Clean old releases";

    cd {{ $releases_dir }};
    rm -rf $(ls -t | tail -n +{{ $keep }});

    echo "Old releases have been cleaned";
@endtask

{{-- Buil MKdocs --}}
@task('mkdocs:build', ['on' => $on])
    @foreach($mkdocs as $folder)
        echo "Build Mkdocs ({{ $folder }})";
        cd {{ $release_dir }};
        cd {{ $folder }};

        /home/crm/.local/bin/mkdocs build --strict;
    @endforeach

    echo "Documentations has been build";
@endtask

{{-- Configure shared assets --}}
@task('symlinks:shared', ['on' => $on])
    [ -d {{ $shared_dir }} ] || mkdir -p {{ $shared_dir }};

    @foreach($shared as $item => $type)
        #create shared folder if not exists
        @if($type === 'd')
            mkdir -p {{ $shared_dir }}/{{ $item }}
        @endif

        #// if the item passed exists in the shared folder and in the release folder then
        #// remove it from the release folder;
        #// or if the item passed not existis in the shared folder and existis in the release folder then
        #// move it to the shared folder

        if ( [ -{{ $type }} '{{ $shared_dir }}/{{ $item }}' ] && [ -{{ $type }} '{{ $release_dir }}/{{ $item }}' ] ); then
            rm -rf {{ $release_dir }}/{{ $item }};
            echo "rm -rf {{ $release_dir }}/{{ $item }}";
        elif ( [ ! -{{ $type }} '{{ $shared_dir }}/{{ $item }}' ]  && [ -{{ $type }} '{{ $release_dir }}/{{ $item }}' ] ); then
            mv {{ $release_dir }}/{{ $item }} {{ $shared_dir }}/{{ $item }};
            echo "mv {{ $release_dir }}/{{ $item }} {{ $shared_dir }}/{{ $item }}";
        fi

        ln -nfs {{ $shared_dir }}/{{ $item }} {{ $release_dir }}/{{ $item }}
        echo "Symlink has been set for {{ $release_dir }}/{{ $item }}"
    @endforeach

    echo "All symlinks have been set"
@endtask

{{-- Restart queue workers --}}
@task('queue:restart', ['on' => $on])
{{--    sudo systemctl restart pricing@*--}}
    echo "Queue workers have been restarted"
@endtask

{{-- Configure current folder --}}
@task('symlinks:current', ['on' => $on])
    ln -nfs {{ $release_dir }} {{ $current_dir }};
    echo "Current symlink have been set"
@endtask
