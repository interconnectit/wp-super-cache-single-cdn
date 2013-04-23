WP Super Cache single cdn
=========================

This is an mu-plugin that allows you to use a single cdn distribution for an
entire multisite network by disabling the ms-files.php rewrite.

The reason you would want this is that you don't have to configure a cdn for
each site on your network. Set it up once and all sites will use it.

## Usage

Place the PHP file in this repository in your `wp-content/mu-plugins` folder and
configure the CDN via the WP Super Cache settings.

## Pros

* You only need to set up one cdn distribution
* Static files will already be cached for all sites on network
* Super Cache CDN settings are synced automatically across the network
* No ms-files.php rewrite rule therefore no PHP streaming = less server load

## Cons

* No /files/ rewrite, files are served from their actual URL in wp-content
* ...
