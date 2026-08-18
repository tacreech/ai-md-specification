<?php
/**
 * Plugin Name: AI.md Catalogue
 * Description: Serves an author-maintained AI.md catalogue and discovery sitemap.
 * Version: 1.0.0
 * Author: T.A. Creech
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ai-md-catalogue
 *
 * SPDX-License-Identifier: GPL-2.0-or-later
 */

defined('ABSPATH') || exit;

/**
 * Return the fixed source path for the canonical catalogue.
 */
function ai_md_catalogue_file() {
    return apply_filters('ai_md_catalogue_file', __DIR__ . '/AI.md');
}

/**
 * Return the URL path for a site-root resource, including subdirectory installs.
 */
function ai_md_catalogue_url_path($resource) {
    $path = wp_parse_url(home_url('/' . ltrim($resource, '/')), PHP_URL_PATH);

    return is_string($path) ? $path : '';
}

/**
 * Return the current request path.
 */
function ai_md_catalogue_request_path() {
    if (empty($_SERVER['REQUEST_URI'])) {
        return '';
    }

    $path = wp_parse_url(wp_unslash($_SERVER['REQUEST_URI']), PHP_URL_PATH);

    return is_string($path) ? $path : '';
}

/**
 * Allow only GET and HEAD for generated public resources.
 */
function ai_md_catalogue_allow_read_request() {
    $method = isset($_SERVER['REQUEST_METHOD'])
        ? strtoupper(sanitize_text_field(wp_unslash($_SERVER['REQUEST_METHOD'])))
        : 'GET';

    if ($method === 'GET' || $method === 'HEAD') {
        return $method;
    }

    status_header(405);
    header('Allow: GET, HEAD');
    exit;
}

/**
 * Send cache validators and return true when a 304 response was emitted.
 */
function ai_md_catalogue_send_validators($file) {
    $modified = filemtime($file);
    $etag_hash = sha1_file($file);

    if ($modified) {
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $modified) . ' GMT');
    }

    $etag = $etag_hash ? '"' . $etag_hash . '"' : '';

    if ($etag !== '') {
        header('ETag: ' . $etag);
    }

    $if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH'])
        ? trim(wp_unslash($_SERVER['HTTP_IF_NONE_MATCH']))
        : '';
    $if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])
        ? strtotime(wp_unslash($_SERVER['HTTP_IF_MODIFIED_SINCE']))
        : false;

    $etag_matches = $etag !== '' && $if_none_match !== '' && $if_none_match === $etag;
    $date_matches = $modified && $if_modified_since && $if_modified_since >= $modified;

    if (($if_none_match !== '' && $etag_matches) || ($if_none_match === '' && $date_matches)) {
        status_header(304);
        exit;
    }
}

/**
 * Serve the canonical catalogue at /AI.md.
 */
function ai_md_catalogue_serve() {
    $request_path = ai_md_catalogue_request_path();
    $catalogue_path = ai_md_catalogue_url_path('/AI.md');

    if ($request_path === '' || $catalogue_path === '') {
        return;
    }

    if ($request_path !== $catalogue_path) {
        if (strcasecmp($request_path, $catalogue_path) === 0) {
            wp_safe_redirect(home_url('/AI.md'), 301);
            exit;
        }

        return;
    }

    $method = ai_md_catalogue_allow_read_request();
    $file = ai_md_catalogue_file();

    if (!is_string($file) || !is_readable($file)) {
        status_header(404);
        header('Content-Type: text/plain; charset=UTF-8');

        if ($method === 'GET') {
            echo 'AI.md catalogue not found.';
        }

        exit;
    }

    status_header(200);
    header('Content-Type: text/markdown; charset=UTF-8');
    header('Content-Disposition: inline; filename="AI.md"');
    header('Cache-Control: public, max-age=3600');
    header('X-Content-Type-Options: nosniff');
    header('X-Robots-Tag: index, follow');
    ai_md_catalogue_send_validators($file);

    $size = filesize($file);

    if ($size !== false) {
        header('Content-Length: ' . $size);
    }

    if ($method === 'GET') {
        readfile($file);
    }

    exit;
}
add_action('template_redirect', 'ai_md_catalogue_serve', 0);

/**
 * Return the catalogue sitemap URL.
 */
function ai_md_catalogue_sitemap_url() {
    return home_url('/ai-catalogue-sitemap.xml');
}

/**
 * Serve a one-entry XML sitemap for the catalogue.
 */
function ai_md_catalogue_serve_sitemap() {
    $request_path = ai_md_catalogue_request_path();
    $sitemap_path = ai_md_catalogue_url_path('/ai-catalogue-sitemap.xml');

    if ($request_path === '' || $request_path !== $sitemap_path) {
        return;
    }

    $method = ai_md_catalogue_allow_read_request();
    $file = ai_md_catalogue_file();

    if (!is_string($file) || !is_readable($file)) {
        status_header(404);
        exit;
    }

    $catalogue_url = htmlspecialchars(home_url('/AI.md'), ENT_QUOTES | ENT_XML1, 'UTF-8');
    $modified = filemtime($file);
    $last_modified = $modified ? gmdate('c', $modified) : gmdate('c');
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    $xml .= "  <url>\n";
    $xml .= '    <loc>' . $catalogue_url . "</loc>\n";
    $xml .= '    <lastmod>' . $last_modified . "</lastmod>\n";
    $xml .= "  </url>\n";
    $xml .= "</urlset>\n";

    status_header(200);
    header('Content-Type: application/xml; charset=UTF-8');
    header('Cache-Control: public, max-age=3600');
    header('X-Content-Type-Options: nosniff');
    header('Content-Length: ' . strlen($xml));

    if ($method === 'GET') {
        echo $xml;
    }

    exit;
}
add_action('template_redirect', 'ai_md_catalogue_serve_sitemap', 0);

/**
 * Advertise the catalogue sitemap in WordPress's virtual robots.txt.
 */
function ai_md_catalogue_robots($output, $public) {
    if (!$public) {
        return $output;
    }

    $entry = 'Sitemap: ' . ai_md_catalogue_sitemap_url();

    if (strpos($output, $entry) === false) {
        $output = rtrim($output) . "\n\n" . $entry . "\n";
    }

    return $output;
}
add_filter('robots_txt', 'ai_md_catalogue_robots', 99, 2);

/**
 * Add an ordinary, human-accessible discovery link.
 */
function ai_md_catalogue_footer_link() {
    if (!apply_filters('ai_md_catalogue_footer_link_enabled', true)) {
        return;
    }

    echo '<div class="ai-md-catalogue-link-wrap">';
    echo '<a class="ai-md-catalogue-link" href="' . esc_url(home_url('/AI.md')) . '" type="text/markdown">';
    echo esc_html__('Canonical book catalogue', 'ai-md-catalogue');
    echo '</a>';
    echo '</div>';
}
add_action('wp_footer', 'ai_md_catalogue_footer_link', 99);

/**
 * Keep the automatic discovery link subtle but visible and focusable.
 */
function ai_md_catalogue_footer_style() {
    if (!apply_filters('ai_md_catalogue_footer_link_enabled', true)) {
        return;
    }

    echo '<style id="ai-md-catalogue-style">';
    echo '.ai-md-catalogue-link-wrap{padding:.75rem 1rem;text-align:center;font-size:.78rem;}';
    echo '.ai-md-catalogue-link{color:inherit;opacity:.65;}';
    echo '.ai-md-catalogue-link:hover,.ai-md-catalogue-link:focus-visible{opacity:1;}';
    echo '</style>';
}
add_action('wp_head', 'ai_md_catalogue_footer_style', 99);
