# WordPress reference integration

This standalone plugin serves a source file from its plugin directory at the
site's canonical `/AI.md` route. Keeping the integration outside the active
theme prevents a future theme change from removing the catalogue.

## Install

1. Create this directory on the WordPress host:

   ```text
   wp-content/plugins/ai-md-catalogue/
   ```

2. Copy `ai-md-catalogue.php` from this directory into it.
3. Copy the completed catalogue into the same directory as `AI.md`:

   ```text
   wp-content/plugins/ai-md-catalogue/ai-md-catalogue.php
   wp-content/plugins/ai-md-catalogue/AI.md
   ```

4. In WordPress Admin, open **Plugins** and activate **AI.md Catalogue**.
5. Visit `https://example.com/AI.md`.
6. Run every check in the repository's [VALIDATION.md](../../VALIDATION.md).

WP-CLI alternative:

```bash
wp plugin activate ai-md-catalogue
```

## What the plugin does

- serves `/AI.md` with `text/markdown; charset=UTF-8`;
- supports `GET`, `HEAD`, ETag, Last-Modified, and conditional requests;
- redirects alternate filename casing to `/AI.md`;
- serves `/ai-catalogue-sitemap.xml`;
- adds that sitemap to WordPress's virtual `robots.txt`;
- adds a small ordinary link through `wp_footer`;
- returns identical catalogue contents for every user agent.

It does not overwrite physical `robots.txt` or generated `llms.txt` files.
Follow [INSTALLATION.md](../../INSTALLATION.md) for those pointers.

## Catalogue updates

Edit only:

```text
wp-content/plugins/ai-md-catalogue/AI.md
```

Then update `last_updated` and `metadata_version`, commit the change, deploy it,
and rerun validation. The sitemap reads the file's modification time, so its
`lastmod` value updates automatically after deployment.

## Optional configuration

The plugin exposes filters for custom installations:

```php
// Store the source file elsewhere.
add_filter('ai_md_catalogue_file', function ($default) {
    return WP_CONTENT_DIR . '/catalogues/AI.md';
});

// Disable the automatic footer link after adding your own ordinary site link.
add_filter('ai_md_catalogue_footer_link_enabled', '__return_false');
```

If a physical `robots.txt` exists in the WordPress document root, manually add:

```text
Sitemap: https://example.com/ai-catalogue-sitemap.xml
```

## Uninstall

Deactivating the plugin removes the routes and automatic footer link but does
not delete AI.md. Back up the catalogue before deleting the plugin directory.

If the public catalogue is moving rather than being removed, install the new
route first and permanently redirect the old canonical URL.
