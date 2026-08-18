# Installing AI.md

This guide publishes an AI.md catalogue so humans, crawlers, search systems,
and user-directed agents can discover and retrieve it reliably.

## Before you begin

You need:

- control of the canonical website;
- an author-approved catalogue based on [AI.md](AI.md);
- HTTPS on the site;
- access to the site's files, deployment repository, CMS, or hosting panel;
- a way to inspect HTTP response headers, such as `curl`.

Do not publish inferred representation, relationship, or content-warning data.
Use `unknown` until the catalogue owner approves a value.

## Installation overview

Every installation has four layers:

1. **Source:** one version-controlled AI.md file.
2. **Serving:** a canonical `/AI.md` URL returning UTF-8 `text/markdown`.
3. **Discovery:** an ordinary HTML link plus optional sitemap, `robots.txt`, and
   `llms.txt` pointers.
4. **Verification:** HTTP, content, crawler, and update checks.

## Step 1: Create the catalogue

1. Copy the repository's [AI.md](AI.md) template.
2. Replace the catalogue title, owner, domain, dates, and URLs.
3. Add one `## Series:` section for each series, if applicable.
4. Add one `### Work Title` section for every work.
5. Delete example-only text.
6. Mark unavailable information as `unknown`.
7. Set `specification_version: 1.0.0`.
8. Set `metadata_version: 1.0.0` for the first publication.
9. Set `last_updated` to the publication date in `YYYY-MM-DD` format.
10. Obtain the catalogue owner's approval.

Keep this source file in version control. Do not maintain separate hand-edited
copies for different crawlers.

## Step 2: Choose a serving method

Use the simplest method supported by the site.

### Option A: Static root file

Use this option when files placed in the site's document root are served before
the CMS router.

1. Upload AI.md to the website's document root beside files such as
   `robots.txt`.
2. Confirm that `https://example.com/AI.md` returns the file.
3. Configure the server or hosting panel to return:

   ```text
   Content-Type: text/markdown; charset=UTF-8
   Content-Disposition: inline; filename="AI.md"
   X-Content-Type-Options: nosniff
   X-Robots-Tag: index, follow
   Cache-Control: public, max-age=3600
   ```

4. If the host returns `text/plain` or `text/html`, add a MIME mapping for `.md`
   files or use an application route instead.
5. Redirect alternate casing to `/AI.md` when practical.

Example Apache MIME declaration:

```apache
AddType text/markdown .md
```

Example Nginx route:

```nginx
location = /AI.md {
    default_type text/markdown;
    add_header Content-Disposition 'inline; filename="AI.md"';
    add_header X-Content-Type-Options nosniff;
    add_header X-Robots-Tag 'index, follow';
    add_header Cache-Control 'public, max-age=3600';
    try_files /AI.md =404;
}

location ~* ^/ai\.md$ {
    return 301 /AI.md;
}
```

Server configuration varies. Validate the actual response rather than assuming
the filename produces the correct media type.

### Option B: WordPress reference plugin

Use this option when WordPress owns the root route or when the host does not
provide convenient MIME/header controls.

Follow [`integrations/wordpress/README.md`](integrations/wordpress/README.md).
The plugin:

- serves its adjacent AI.md file at `/AI.md`;
- redirects alternate filename casing;
- returns the required representation headers;
- supports `GET` and `HEAD`;
- serves `/ai-catalogue-sitemap.xml`;
- adds the sitemap to WordPress's virtual `robots.txt`;
- adds a small, ordinary catalogue link through `wp_footer`.

The plugin is a reference implementation, not a required part of the standard.

### Option C: Another CMS or application

Create a route with the behavior specified in section 3 of
[SPECIFICATIONS.md](SPECIFICATIONS.md). Keep the source file separate from the
route code and return its bytes without converting it to HTML.

The route should:

1. match only the canonical path;
2. read one fixed, trusted source file;
3. return `404` when that file is missing;
4. allow only `GET` and `HEAD`;
5. set the required content type and recommended headers;
6. avoid user-agent-specific output;
7. terminate before the CMS renders an HTML template.

## Step 3: Add an ordinary HTML discovery link

Add a real anchor element to a crawlable page, preferably a site-wide footer or
the canonical series/catalogue page:

```html
<a href="https://example.com/AI.md" type="text/markdown">
  Canonical book catalogue
</a>
```

The link may be subtle, but it must remain available to humans and assistive
technology. Do not use `display: none`, `hidden`, off-screen positioning, or
zero-size text.

Example subtle styling:

```css
.ai-catalogue-link {
  font-size: 0.78rem;
  color: inherit;
  opacity: 0.65;
}

.ai-catalogue-link:hover,
.ai-catalogue-link:focus-visible {
  opacity: 1;
}
```

## Step 4: Add crawler discovery pointers

These pointers provide redundancy. The HTML link remains required.

### XML sitemap

Create `/ai-catalogue-sitemap.xml`:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>https://example.com/AI.md</loc>
    <lastmod>YYYY-MM-DD</lastmod>
  </url>
</urlset>
```

`lastmod` must reflect the catalogue's real modification date, not merely the
time the sitemap was generated.

### robots.txt

Add the sitemap's absolute URL to `/robots.txt`:

```text
Sitemap: https://example.com/ai-catalogue-sitemap.xml
```

If a physical `robots.txt` file exists, edit that file. A CMS filter that
changes only a virtual `robots.txt` response will not affect a physical file
that the web server serves first.

Do not add a `Disallow` rule covering `/AI.md`.

### llms.txt

If the site publishes `/llms.txt`, add a concise link in an appropriate H2
section:

```markdown
## Canonical Book Metadata

- [AI Catalogue](https://example.com/AI.md): Creator-maintained canonical metadata for the site's creative works.
```

If a plugin regenerates `llms.txt`, use its supported hook or verify the pointer
after every regeneration. Do not assume a one-time manual edit will persist.

## Step 5: Verify the deployment

Run:

```bash
curl -sSIL https://example.com/AI.md
curl -fsSL https://example.com/AI.md | sed -n '1,40p'
curl -sSIL https://example.com/ai.md
curl -sSIL https://example.com/ai-catalogue-sitemap.xml
curl -fsSL https://example.com/robots.txt
curl -fsSL https://example.com/llms.txt
```

Expected results:

- `/AI.md` returns `200` and `text/markdown; charset=UTF-8`;
- the body begins with the expected H1 and catalogue metadata;
- alternate casing redirects permanently to `/AI.md` or returns `404`, not a
  competing catalogue;
- the XML sitemap returns `200` and contains the canonical URL;
- `robots.txt` advertises the sitemap;
- `llms.txt`, when present, links to AI.md;
- an ordinary site page contains the catalogue anchor.

Test at least one complete user-agent string, but expect identical content:

```bash
curl -fsSL \
  -A 'Mozilla/5.0 (compatible; ExampleCrawler/1.0)' \
  https://example.com/AI.md | sha256sum

curl -fsSL \
  -A 'Mozilla/5.0' \
  https://example.com/AI.md | sha256sum
```

The hashes should match.

For the complete checklist, see [VALIDATION.md](VALIDATION.md).

## Step 6: Establish ownership and maintenance

Record in the site's repository or operations notes:

- the source path of AI.md;
- the canonical public URL;
- the code or server rule that serves it;
- who may approve metadata changes;
- how `llms.txt` and `robots.txt` are generated;
- the verification commands;
- the metadata versioning policy.

Give a maintaining coding agent [AGENT-GUIDE.md](AGENT-GUIDE.md). The agent
should update the single source file, version/date fields, discovery resources
when paths change, and deployment tests in the same change.

## Troubleshooting

### The URL returns WordPress HTML

The request fell through to the normal template. Confirm the plugin is active,
the source file is readable, and no caching layer has stored the old response.

### The URL returns `application/octet-stream`

The server does not know the Markdown media type. Add a MIME declaration or use
an application route that explicitly sets `Content-Type`.

### The URL downloads instead of displaying inline

Set `Content-Disposition: inline; filename="AI.md"` and confirm that no proxy or
host overrides it.

### Humans can fetch it but a crawler receives `429`

Compare access logs by IP, user agent, path, and time. Test the same full user
agent from another client. Check host-level rate limiting, CDN/WAF rules, and
security plugins. Do not change the catalogue format to work around an
unrelated network block.

### The sitemap pointer does not appear in robots.txt

Check whether the site has a physical `robots.txt`. Physical files commonly
take precedence over CMS-generated virtual responses.

### A page reader rejects `text/markdown`

That reader lacks Markdown support; the deployment is not necessarily broken.
Keep the correct registered media type. Search crawlers, other agents, and
ordinary HTTP clients may still retrieve it successfully.
