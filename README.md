# Runner

Runner is a way to run your Hiraeth application using Docker and FrankenPHP.  The only pre-requisite should be a relatively modern version of Docker.

## Install

In your project root:

```
composer require hiraeth/runner
```

## Basic Usage

```
cd runner
docker compose up
```

Executing this will build FrankenPHP along with a recent version of PHP.  It should be noted that out of the box only a minimal set of PHP extensions are provided/built for the PHP image.  To add extensions, from the `runner` directory, simply:

```
touch extensions/<extension>
```

For example:

```
touch extensions/sqlite3
```

Then re-run with:

```
docker compose up --build
```

## Caddy

Runner is powered by Caddy, if you need additional plugins you can add them to the `build.args` file as `--with` arguments for the `xcaddy` build step.  Similarly you would want to re-run with the `--build` option.  You can modify the Caddyfile in the `caddy` sub-directory.  By default tls will be enabled with `internal` and all ports will be opened on all interfaces.
