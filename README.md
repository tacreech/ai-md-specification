# AI.md specification

AI.md is an open Markdown specification for author-maintained metadata about
creative works.

Authors often publish metadata across multiple platforms, where it becomes
inconsistent, incomplete, or inferred by third parties. AI.md provides one
canonical file that humans, search engines, and AI systems can reference.

The specification is deliberately implementation-independent. A catalogue may
be served as a static file, by a content-management system, or by any other
route that returns the required Markdown representation.

## Start here

1. Read [SPECIFICATIONS.md](SPECIFICATIONS.md).
2. Copy [AI.md](AI.md) and replace every placeholder with author-approved data.
3. Follow [INSTALLATION.md](INSTALLATION.md) to publish and advertise the file.
4. Follow [MAINTENANCE.md](MAINTENANCE.md) whenever the catalogue changes.
5. Run the conformance checks in [VALIDATION.md](VALIDATION.md).

WordPress users may use the reference plugin in
[`integrations/wordpress`](integrations/wordpress/README.md).

If a coding agent will perform the installation, give it
[AGENT-GUIDE.md](AGENT-GUIDE.md) with the target site's repository and hosting
details.

## Design goals

- **Authoritative:** the creator or authorized publisher controls the data.
- **Human-readable:** no proprietary reader or schema registry is required.
- **Machine-readable:** stable headings, field names, lists, and URLs can be
  parsed without guessing.
- **Discoverable:** ordinary site navigation and crawler discovery files point
  to the catalogue.
- **Safe to consume:** the file contains metadata and provenance guidance, not
  executable instructions or attempts to control an agent.
- **Portable:** the metadata is not tied to a retailer, CMS, host, or AI vendor.

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

This repository contains version 1.0.0 of the specification. Community
implementations may extend it with additional fields as described in the
extension rules.

## License

The specification, documentation, and template are licensed under
[CC BY-SA 4.0](LICENSE.md). The WordPress reference integration is licensed
under GPL-2.0-or-later.
