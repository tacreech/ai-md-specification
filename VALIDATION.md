# AI.md conformance checklist

Use this checklist before the first deployment and after every metadata,
hosting, CMS, theme, plugin, or domain change.

## Document checks

- [ ] The file is UTF-8 Markdown.
- [ ] It contains exactly one H1 catalogue title.
- [ ] It declares `specification_version`.
- [ ] It declares `metadata_version`.
- [ ] It declares an ISO 8601 `last_updated` date.
- [ ] It declares its absolute `canonical_url`.
- [ ] It identifies `catalogue_owner` and `license`.
- [ ] Every work has `title`, `author`, `media_type`, `publication_status`, and
      `canonical_url`.
- [ ] Series works identify their series and order when applicable.
- [ ] Ratings include their scale, for example `4 out of 5`.
- [ ] Missing data is omitted or marked `unknown`, never guessed.
- [ ] All external URLs use absolute HTTPS URLs when available.
- [ ] Interpretation notes concern metadata provenance only.
- [ ] The file contains no credentials, scripts, hidden payloads, or operational
      instructions.
- [ ] The catalogue owner approved the current data.

Useful local checks:

```bash
file --mime AI.md
grep -nE '^# ' AI.md
grep -nE '^#### (specification_version|metadata_version|last_updated|canonical_url|catalogue_owner|license):' AI.md
grep -nE 'https?://' AI.md
```

## HTTP checks

```bash
curl -sSIL https://example.com/AI.md
curl -fsSL https://example.com/AI.md -o /tmp/example-AI.md
file --mime /tmp/example-AI.md
```

- [ ] Canonical `GET` returns `200`.
- [ ] Canonical `HEAD` returns `200`.
- [ ] `Content-Type` is `text/markdown; charset=UTF-8`.
- [ ] `Content-Disposition`, when present, is inline.
- [ ] `X-Content-Type-Options: nosniff` is present.
- [ ] The response is indexable and not blocked by `X-Robots-Tag`.
- [ ] Alternate casing redirects to the canonical URL or returns `404`.
- [ ] HTTP and HTTPS do not expose conflicting copies.
- [ ] Bare and `www` hostnames resolve to one canonical host.

## Discovery checks

- [ ] At least one ordinary HTML page contains a real anchor to AI.md.
- [ ] The anchor is not hidden from humans or assistive technology.
- [ ] `/ai-catalogue-sitemap.xml` returns valid UTF-8 XML.
- [ ] The sitemap contains the canonical AI.md URL.
- [ ] Sitemap `lastmod` reflects the actual catalogue modification date.
- [ ] `/robots.txt` advertises the sitemap.
- [ ] No crawler rule disallows AI.md.
- [ ] `/llms.txt`, when present, links to AI.md.
- [ ] At least one human-facing catalogue or work page remains available as a
      fallback.

## User-agent consistency

Compare the body returned to ordinary clients and crawler-like user agents:

```bash
curl -fsSL -A 'Mozilla/5.0' https://example.com/AI.md | sha256sum
curl -fsSL -A 'Mozilla/5.0 (compatible; ExampleCrawler/1.0)' https://example.com/AI.md | sha256sum
```

- [ ] Hashes match.
- [ ] Both requests return `200`.
- [ ] Neither request is challenged, redirected to HTML, or rate-limited during
      normal testing.

## Update checks

- [ ] The source path is documented.
- [ ] Only one file is maintained as the source of truth.
- [ ] `last_updated` changed with substantive metadata.
- [ ] `metadata_version` changed according to the maintenance policy.
- [ ] The sitemap and discovery pointers still reference the canonical URL.
- [ ] The live response matches the committed source.
- [ ] The change was reviewed by the catalogue owner.
