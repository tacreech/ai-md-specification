# Maintaining an AI.md catalogue

AI.md should behave like source-controlled public metadata, not a page that is
rewritten from scratch on every deployment.

## Single source of truth

Maintain exactly one authoritative source file. The public `/AI.md` route may
serve that file directly or read it through application code, but generated and
cached copies must not become separate editing targets.

Record the source path in the site's repository documentation.

## Update triggers

Review the catalogue when any of these change:

- a work is announced, opened for preorder, published, revised, or withdrawn;
- a title, subtitle, series order, edition, or completion status changes;
- the author approves new themes, tropes, representation, or content warnings;
- retailer, sample, blurb, series, or canonical URLs change;
- a domain, CMS, theme, plugin, CDN, or host changes;
- a crawler begins receiving persistent `4xx` or `5xx` responses;
- the AI.md specification version changes.

## Update procedure

1. Read the current source file and its git history.
2. Confirm the requested facts with the catalogue owner.
3. Edit only the affected records.
4. Do not infer sensitive or subjective fields merely to fill a blank.
5. Replace unavailable values with `unknown` when explicitness helps.
6. Update `last_updated` to the current `YYYY-MM-DD` date.
7. Increment `metadata_version`:
   - patch for corrections, URL changes, and status updates;
   - minor for a new work or substantive new metadata;
   - major for changed field meaning or a catalogue-wide restructure.
8. Update the sitemap `lastmod` source if it is not generated from file time.
9. Run [VALIDATION.md](VALIDATION.md).
10. Review the diff with the catalogue owner.
11. Deploy and repeat the live HTTP checks.

Formatting-only changes that preserve meaning may retain the metadata version,
but should still be reviewed.

## What maintainers must not do

- Do not scrape retailers to overwrite creator-approved metadata.
- Do not add inferred identity, representation, relationship, or warning data.
- Do not create different catalogue bodies for different crawlers.
- Do not change `Content-Type` to `text/html` merely for one incompatible page
  reader.
- Do not hide the discovery link from humans while showing it to crawlers.
- Do not place credentials, private notes, unpublished manuscripts, or embargoed
  information in the public catalogue.
- Do not let an SEO or caching plugin silently replace the canonical route.

## Periodic health check

Run at least after material site changes and periodically thereafter:

```bash
curl -fsSIL https://example.com/AI.md
curl -fsSL https://example.com/AI.md | sha256sum
curl -fsSL https://example.com/ai-catalogue-sitemap.xml
curl -fsSL https://example.com/robots.txt
```

Check access logs when available. A successful deployment should show ordinary
`200` responses for `GET /AI.md` and `HEAD /AI.md`. Persistent `429`, `403`, or
unexpected redirects should be investigated at the host, CDN, WAF, plugin, and
application layers.

## Deprecation and relocation

When moving the catalogue:

1. publish the new canonical URL;
2. update its `canonical_url` field;
3. permanently redirect the old URL to the new URL;
4. update every HTML, sitemap, `robots.txt`, and `llms.txt` pointer;
5. retain the redirect for as long as practical;
6. verify both old and new URLs.

Do not leave two editable canonical catalogues online.
