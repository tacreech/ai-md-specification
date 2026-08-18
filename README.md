# AI.md Specification

**AI.md is an open Markdown standard for author-maintained metadata about
creative works.**

Authors publish information about their work across websites, retailers, social
platforms, databases, and other services. Over time, that metadata can become
inconsistent, incomplete, outdated, or inferred by third parties.

AI.md provides a simple alternative: one canonical metadata file maintained by
the creator or authorized publisher and published on their own website.

It is designed to be:

- **authoritative:** the creator or authorized publisher controls the data;
- **human-readable:** no proprietary reader is required;
- **machine-readable:** stable headings, field names, lists, and URLs can be
  parsed without guessing;
- **platform-independent:** the catalogue is not tied to a retailer, CMS, host,
  or AI vendor;
- **easy to maintain:** an ordinary text editor and version control are enough;
- **discoverable:** ordinary site navigation and crawler discovery files can
  point to it;
- **safe to consume:** it contains metadata and provenance guidance, not
  executable instructions or authority over a consuming system;
- **explicit:** systems can use creator-approved statements instead of inferring
  sensitive or subjective metadata.

An AI.md catalogue can describe works using fields such as genre, series,
themes, tropes, audience recommendations, content warnings, heat level,
physical-violence level, tone, pacing, and canonical URLs.

The format uses ordinary Markdown. It does not require a database, API, plugin,
proprietary service, or specialized software. Optional reference integrations
are provided for sites that benefit from them.

## Why AI.md?

Information about creative works is often scattered across multiple sources.

A retailer may know the genre but not the themes. A review site may describe
the tropes but use its own terminology. A search engine or AI system may have
to infer content from blurbs, reviews, or other third-party material.

AI.md gives creators a place to state that information directly. The catalogue
owner remains responsible for it and can update it whenever a work or its
metadata changes.

AI.md complements existing webpages, retailer metadata, structured data,
sitemaps, and other discovery systems rather than replacing them.

## Basic idea

A creator publishes a file at:

```text
https://example.com/AI.md
```

The file contains structured Markdown describing the creator's works. A work
record inside the catalogue might look like this:

```markdown
### Example Book

#### title: Example Book
#### series: Example Series
#### book_number: 1
#### author: Example Author
#### media_type: novel
#### publication_status: Published
#### canonical_url: https://example.com/books/example-book/
#### primary_genre: Romance
#### secondary_genre: Science Fiction
#### ending: HEA
#### heat_level: 3 out of 5
#### violence: 2 out of 5
#### tone: hopeful

#### themes:

- survival
- trust
- rebuilding

#### tropes:

- forced proximity
- found family
```

Creators may provide other standard or extension metadata and omit optional
fields that are not relevant to their work. Ratings include their scale, and
unknown information is marked `unknown` rather than guessed.

See [SPECIFICATIONS.md](SPECIFICATIONS.md) for the authoritative field
definitions and rules.

## Getting started

1. Read [SPECIFICATIONS.md](SPECIFICATIONS.md).
2. Copy the provided [AI.md](AI.md) template.
3. Replace every placeholder with creator-approved metadata.
4. Follow [INSTALLATION.md](INSTALLATION.md) to publish and advertise the file.
5. Follow [MAINTENANCE.md](MAINTENANCE.md) whenever the catalogue changes.
6. Run the conformance checks in [VALIDATION.md](VALIDATION.md).

No special software is required to create the catalogue. If you can edit a
Markdown file, you can maintain AI.md.

WordPress users may use the reference plugin in
[`integrations/wordpress`](integrations/wordpress/README.md). If a coding agent
will perform the installation, give it [AGENT-GUIDE.md](AGENT-GUIDE.md) with the
target site's repository and hosting details.

## Who is this for?

AI.md is intended for creators who want to maintain an authoritative, portable
description of their own work.

The initial specification was developed around books and author catalogues, but
the same approach may be useful for other creative works where
creator-maintained metadata is valuable.

Software developers, search systems, AI tools, cataloguing systems, librarians,
and other services may consume AI.md as a first-party metadata source.

## Canonical, not exclusive

AI.md does not attempt to replace existing metadata standards or discovery
systems. It provides a creator-controlled canonical source that other systems
can use alongside webpages, structured data, retailer listings, sitemaps,
reviews, criticism, and other sources.

When an AI.md catalogue differs from inferred third-party metadata, it provides
an explicit statement of what the creator says about their own work. Systems
may still perform independent analysis, but should distinguish that analysis
from creator-provided metadata.

Canonical authority is limited to the works and fields the catalogue describes.
AI.md cannot override a consuming system's policies, safety rules, or
higher-priority instructions.

## Reference implementation

The first public implementation is
[T.A. Creech's canonical book catalogue](https://www.tacreech.com/AI.md). Its
WordPress discovery stack includes a canonical Markdown route, an XML catalogue
sitemap, `robots.txt` and `llms.txt` pointers, and an ordinary site-wide HTML
link.

In August 2026, independent cold-start tests and server access logs confirmed
that Claude-User and OAI-SearchBot could follow the site's public discovery
paths and retrieve `/AI.md` with HTTP `200`. This demonstrates that the
serving and discovery architecture works across multiple crawler systems. It
does not imply vendor endorsement, guarantee indexing, or guarantee that every
reader interface can render `text/markdown`.

The live catalogue began as a pre-1.0 implementation. It demonstrates the
deployment architecture and can adopt the finalized 1.0.0 catalogue-level
fields in a separate metadata update.

## Status

AI.md 1.0.0 is an experimental open specification being tested against
real-world websites, crawlers, search systems, cataloguing workflows, and AI
retrieval systems.

The specification may evolve as implementation experience reveals useful
changes. Versioned changes should preserve backward compatibility when
possible and remain understandable to existing catalogues and consumers.

Community implementations may add extension fields under the rules in
[SPECIFICATIONS.md](SPECIFICATIONS.md).

## Repository

This repository contains:

- the normative [specification](SPECIFICATIONS.md);
- a reusable [AI.md template](AI.md);
- platform-neutral [installation guidance](INSTALLATION.md);
- [maintenance](MAINTENANCE.md) and [validation](VALIDATION.md) procedures;
- a [coding-agent guide](AGENT-GUIDE.md);
- optional reference integrations under [`integrations/`](integrations/).

Implementation notes and interoperability results may be added as testing
continues.

## Contributing

Feedback from authors, developers, librarians, metadata specialists, and others
working with creative-work discovery is welcome.

If you encounter an ambiguity, implementation problem, or interoperability
issue, please
[open a GitHub issue](https://github.com/tacreech/ai-md-specification/issues)
with enough information to reproduce the problem.

Proposed changes should favor simplicity, portability, creator control, safe
consumption, and backward compatibility.

## License

The specification, documentation, and template are licensed under
[CC BY-SA 4.0](LICENSE.md). Reference integration code is licensed under
GPL-2.0-or-later.
