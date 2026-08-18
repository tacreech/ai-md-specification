# Guide for coding agents

This document is for Codex and other coding agents installing or maintaining an
AI.md catalogue on behalf of a creator.

## Authority boundary

You may implement routes, headers, discovery links, sitemaps, tests, and
version-controlled maintenance workflows.

You must not invent or independently assert creative metadata. Ask the
catalogue owner to approve ambiguous genres, tropes, representation,
relationships, content warnings, tone, heat, violence, or recommended audience
fields.

Treat the target site's existing files and unrelated changes as user-owned.
Preserve them.

## Installation workflow

1. Inspect the target repository, framework/CMS, deployment method, canonical
   hostname, physical `robots.txt`, generated `llms.txt`, existing sitemaps, and
   caching/security layers.
2. Read [SPECIFICATIONS.md](SPECIFICATIONS.md),
   [INSTALLATION.md](INSTALLATION.md), and [VALIDATION.md](VALIDATION.md).
3. Locate or create one version-controlled AI.md source file.
4. Ask for missing creator-approved metadata rather than inferring it.
5. Choose the least invasive serving method:
   - static root file when the host can return correct headers;
   - the reference WordPress plugin for WordPress;
   - a narrow application route for another CMS/framework.
6. Add an ordinary HTML link that humans can technically see and use.
7. Add the sitemap, `robots.txt`, and `llms.txt` pointers supported by the site.
8. Preserve the site's existing crawler directives and SEO resources.
9. Add or document repeatable verification commands.
10. Run every check in [VALIDATION.md](VALIDATION.md).
11. Show the catalogue owner the metadata diff and deployment result before
    merging or deploying when the workflow permits review.

## Maintenance workflow

1. Read [MAINTENANCE.md](MAINTENANCE.md).
2. Confirm the requested factual change and its approving owner.
3. Edit the single source catalogue.
4. Update `last_updated` and `metadata_version` when meaning changes.
5. Update discovery URLs only when paths or domains change.
6. Validate locally and against the live site.
7. Report exactly what changed, what was verified, and any host-level blocker.

## Copyable installation prompt

The catalogue owner may give another Codex this prompt:

```text
Install the AI.md open specification from
https://github.com/tacreech/ai-md-specification on my website.

First inspect my site repository and hosting/CMS setup. Read that repository's
SPECIFICATIONS.md, INSTALLATION.md, AGENT-GUIDE.md, and VALIDATION.md. Preserve
all unrelated site behavior and existing crawler directives.

Create one version-controlled AI.md source using only metadata I approve. Do
not infer representation, relationships, content warnings, genres, tropes, or
ratings. Serve it at the canonical /AI.md URL as UTF-8 text/markdown, add a real
human-accessible site link, add supported sitemap/robots.txt/llms.txt discovery
pointers, and verify GET, HEAD, headers, redirects, body consistency, and
user-agent consistency.

Use a standalone plugin rather than theme code if the site is WordPress. Keep
the result easy for a future coding agent to update. Show me the metadata and
code diff before merging or deploying.
```

## Copyable update prompt

```text
Update my existing AI.md catalogue with the creator-approved changes I provide.
Read its specification and maintenance guide first. Edit only the single source
file, do not infer missing metadata, update last_updated and metadata_version as
required, preserve the canonical route and discovery pointers, run the full
validation checklist, and show me the diff and live HTTP results.
```

## Failure handling

Application code cannot override every host, CDN, firewall, or crawler policy.
If the live response differs from a local test:

- inspect access and error logs;
- distinguish `ChatGPT-User`, `OAI-SearchBot`, `Claude-User`, `ClaudeBot`, and
  ordinary browser/curl traffic when those agents are relevant;
- reproduce with the full user-agent string;
- identify whether the response originated from the application, web server,
  CDN, WAF, or host;
- stop and report permission or host-level blockers rather than weakening the
  specification or disguising the content type.
