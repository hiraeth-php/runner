FROM dunglas/frankenphp:1.0.0-builder-bookworm AS builder

# Copy xcaddy in the builder image
COPY --from=caddy:builder /usr/bin/xcaddy /usr/bin/xcaddy

# CGO must be enabled to build FrankenPHP
ENV CGO_ENABLED=1 XCADDY_SETCAP=1 XCADDY_GO_BUILD_FLAGS="-ldflags '-w -s'"
RUN xcaddy build \
	--output /usr/local/bin/frankenphp \
	--with github.com/dunglas/frankenphp=./ \
	--with github.com/dunglas/frankenphp/caddy=./caddy/

FROM dunglas/frankenphp:1.0.0-bookworm as runner

COPY --from=builder /usr/local/bin/frankenphp /user/local/bin/franken/php
COPY ./extensions /extensions

RUN install-php-extensions $(ls -1 /extensions)
