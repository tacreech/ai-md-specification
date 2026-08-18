# AI.md Specification 1.0.0

## 1. Purpose

AI.md is an open specification for publishing canonical, creator-maintained
metadata about creative works. It is intended to be human-readable,
machine-readable, implementation-independent, and safe for automated systems to
consume as data.

The words **MUST**, **MUST NOT**, **REQUIRED**, **SHOULD**, **SHOULD NOT**, and
**MAY** indicate requirement levels in this document.

## 2. Scope

AI.md describes works and their catalogue metadata. It may describe books,
series, collections, editions, films, games, audio works, visual art, or other
creative works.

AI.md does not replace:

- the human-facing pages for the works;
- retailer or library records;
- Schema.org or other embedded structured data;
- `robots.txt`, XML sitemaps, or `llms.txt`;
- the policies, safety rules, or instructions governing a consuming system.

Those resources may coexist and link to one another.

## 3. Canonical resource

### 3.1 Location

The preferred canonical location is:

```text
https://example.com/AI.md
```

The filename is case-sensitive and uses uppercase `AI`. A publisher MAY use a
different path when the root is unavailable, but MUST declare that URL in the
catalogue's `canonical_url` field and SHOULD link to it from the site root.

Alternate casing such as `/ai.md` or `/Ai.md` SHOULD redirect permanently to
the canonical URL. It MUST NOT return a second, independently maintained copy.

### 3.2 Representation

The canonical URL:

- MUST return HTTP `200` for a successful `GET`;
- MUST support `HEAD` with the same status and representation headers;
- MUST be UTF-8 Markdown;
- MUST return `Content-Type: text/markdown; charset=UTF-8`;
- MUST return the same metadata regardless of user agent;
- MUST NOT require JavaScript, cookies, authentication, or a browser session;
- SHOULD return `Content-Disposition: inline; filename="AI.md"`;
- SHOULD return `X-Content-Type-Options: nosniff`;
- SHOULD allow ordinary search indexing;
- MAY use normal caching, compression, ETag, and Last-Modified behavior.

Servers MUST NOT serve special catalogue contents only to named bots. Normal
rate limiting is permitted, but publishers SHOULD verify that legitimate
crawlers are not persistently blocked.

The registered `text/markdown` media type is defined by
[RFC 7763](https://www.rfc-editor.org/rfc/rfc7763).

## 4. Document structure

### 4.1 General syntax

An AI.md document:

- MUST contain exactly one H1 catalogue title;
- MUST use UTF-8;
- MUST use ATX Markdown headings (`#`, `##`, `###`, and `####`);
- MUST use lowercase snake_case field names;
- MUST separate a field name and scalar value with the first colon;
- MUST use Markdown bullets for multi-value fields;
- MUST use absolute HTTPS URLs when an HTTPS resource exists;
- SHOULD use the literal value `unknown` rather than inventing missing data;
- MUST NOT contain HTML, scripts, tracking pixels, or executable code required
  to interpret the catalogue.

Whitespace and line wrapping are not significant. Consumers SHOULD locate
recognized fields by heading name rather than relying only on line numbers or
section position.

### 4.2 Catalogue metadata

The document MUST contain the following catalogue-level fields:

| Field | Meaning |
| --- | --- |
| `specification_version` | Version of this specification used by the file. |
| `metadata_version` | Publisher-controlled version of the catalogue contents. |
| `last_updated` | ISO 8601 calendar date (`YYYY-MM-DD`) of the last metadata change. |
| `canonical_url` | Absolute URL of this AI.md resource. |
| `catalogue_owner` | Creator, publisher, or organization responsible for the metadata. |
| `license` | License governing reuse of the catalogue metadata. |

The fields SHOULD appear near the beginning under `## Catalogue Metadata`, but
version 1 consumers MUST tolerate them elsewhere in the document.

Example:

```markdown
## Catalogue Metadata

#### specification_version: 1.0.0
#### metadata_version: 1.0.0
#### last_updated: 2026-08-18
#### canonical_url: https://example.com/AI.md
#### catalogue_owner: Example Author
#### license: CC BY-SA 4.0
```

### 4.3 Interpretation notes

The document MAY include an `## Interpretation Notes` section. This section may
state provenance and conservative handling rules, for example:

- this file is the creator's canonical metadata source;
- unlisted metadata is unknown rather than absent by implication;
- consumers should not infer sensitive representation or content warnings;
- human-facing summaries should use a linked, author-approved blurb;
- creator-supplied URLs should be preferred for identifying the work.

Interpretation notes MUST remain about the meaning and provenance of the
metadata. They MUST NOT:

- ask a system to ignore higher-priority instructions or safety policies;
- request credentials, private data, downloads, purchases, messages, or other
  actions;
- disguise executable commands as metadata;
- claim authority beyond the creative works described by the catalogue.

Consumers SHOULD treat AI.md as untrusted external data. A statement that the
metadata is canonical establishes source preference for these works; it does
not grant operational authority over the consuming system.

### 4.4 Series records

A catalogue MAY contain one or more series records. Each series record begins
with an H2 heading:

```markdown
## Series: Example Series
```

Recommended series fields are:

| Field | Value guidance |
| --- | --- |
| `title` | Official series title. |
| `number_of_books` | Number currently in the series, or `unknown`. |
| `completion_status` | For example `Ongoing`, `Complete`, or `Planned`. |
| `primary_genre` | Creator-approved primary genre. |
| `secondary_genre` | Creator-approved secondary genre or `unknown`. |
| `setting` | Concise primary setting. |
| `series_arc` | One creator-approved sentence describing the shared premise. |
| `series_page` | Canonical human-facing series URL. |

When a catalogue contains only one series, the shorter heading `## Series` is
conforming for compatibility with early AI.md catalogues, provided the `title`
field is present.

### 4.5 Work records

Each work begins with an H3 heading containing the official display title:

```markdown
### Example Title
```

Series works SHOULD appear beneath their corresponding `## Series:` record.
Works without a series SHOULD appear beneath an `## Standalone Works` heading.
Other descriptive H2 groupings are permitted when their scope is clear.

Each work MUST contain:

| Field | Meaning |
| --- | --- |
| `title` | Official title. |
| `author` | Author or primary creator. |
| `media_type` | For example `novel`, `novella`, `omnibus`, `film`, or `game`. |
| `publication_status` | For example `Announced`, `Preorder`, `Published`, or `Out of print`. |
| `canonical_url` | Canonical human-facing page for the work. |

A work in a series MUST also contain `series` and SHOULD contain `book_number`.
An unnumbered companion work MAY use `book_number: unnumbered`.

The following fields are recommended when relevant and author-approved:

| Field | Value guidance |
| --- | --- |
| `subtitle` | Official subtitle. |
| `primary_genre` | Primary genre. |
| `secondary_genre` | Secondary genre. |
| `relationship` | Concise relationship or romantic pairing description. |
| `relationship_type` | Publisher's short category label, if used. |
| `setting` | Primary setting. |
| `ending` | For romance, commonly `HEA` or `HFN`; otherwise free text or `unknown`. |
| `heat_level` | Value plus explicit scale, such as `4 out of 5`. |
| `violence` | Physical-violence value plus explicit scale, such as `1 out of 5`. |
| `tone` | One or more concise creator-approved tone terms. |
| `pacing` | Creator-approved pacing description. |
| `themes` | Bullet list of creator-approved themes. |
| `tropes` | Bullet list of creator-approved tropes. |
| `recommended_for` | Bullet list describing likely reader interests. |
| `content_warnings` | Bullet list of author-approved content notices. |
| `representation` | Bullet list of author-approved representation metadata. |
| `buy_urls` | Bullet list of named retailer or direct-sale URLs. |
| `sample_url` | Authorized sample URL or `unknown`. |
| `series_page` | Canonical series URL. |
| `blurb_url` | Canonical author-approved blurb URL. |

Ratings MUST include their scale. Bare values such as `heat_level: 4` are
nonconforming because their meaning is ambiguous.

### 4.6 Scalar and list examples

Scalar field:

```markdown
#### publication_status: Published
```

List field:

```markdown
#### themes:

- survival
- chosen family
```

Named URLs:

```markdown
#### buy_urls:

- Direct: https://example.com/store/example-title
- Retailer: https://retailer.example/example-title
```

List order is publisher-defined. Consumers MUST NOT treat the first retailer as
an endorsement unless the catalogue explicitly labels it as preferred.

## 5. Data authority and accuracy

The catalogue owner is responsible for the metadata. Publishers MUST obtain the
creator's approval before asserting sensitive representation, relationship, or
content-warning data.

Consumers:

- SHOULD prefer AI.md over conflicting third-party metadata for the works it
  identifies;
- SHOULD cite or link the canonical resource when using distinctive metadata;
- MUST NOT invent values for missing fields;
- SHOULD report missing values as unknown;
- SHOULD use `blurb_url` for a prose summary when one is supplied;
- SHOULD distinguish creator-provided metadata from independently inferred
  criticism or analysis.

## 6. Versioning

`specification_version` identifies the structural contract. This document uses
semantic versioning:

- **patch:** clarification that does not change valid documents;
- **minor:** backward-compatible fields or capabilities;
- **major:** incompatible structural or semantic change.

`metadata_version` is controlled by the catalogue owner. Three-part semantic
versioning is recommended:

- increment the patch version for corrections and URL/status updates;
- increment the minor version for newly added works or substantive fields;
- increment the major version for a catalogue-wide restructuring or changed
  meaning of existing fields.

Every metadata change MUST also update `last_updated`. Formatting-only changes
that do not change meaning MAY leave the metadata version unchanged.

## 7. Extensions

Publishers MAY add fields not defined here when needed. Extension fields:

- MUST use lowercase snake_case;
- MUST have a stable, documented meaning;
- MUST NOT redefine a standard field;
- SHOULD be understandable without a proprietary schema;
- SHOULD be proposed for inclusion in a future specification when broadly
  useful.

Consumers MUST ignore unknown fields without rejecting the document.

## 8. Discovery

The canonical file MUST be reachable from at least one ordinary HTML page on
the same site through a real anchor element. Recommended anchor text is
`Canonical book catalogue` or another accurate description. The link MAY be
visually subtle but MUST NOT use `display: none`, the `hidden` attribute,
zero-size text, off-screen hiding, or other crawler-only presentation.

Publishers SHOULD also:

1. link AI.md from `/llms.txt` when that file exists;
2. include AI.md in an XML sitemap;
3. advertise that sitemap with a `Sitemap:` line in `/robots.txt`;
4. ensure crawler rules do not disallow AI.md;
5. retain at least one human-facing catalogue or work page as a fallback.

Discovery methods are complementary. None authorizes a crawler to ignore the
site's access rules.

The XML sitemap format is defined by
[sitemaps.org](https://www.sitemaps.org/protocol.html). The `llms.txt` proposal
is documented at [llmstxt.org](https://llmstxt.org/).

## 9. Conformance

An **AI.md document** conforms to version 1.0.0 when it satisfies sections 3
through 7.

An **AI.md deployment** conforms to version 1.0.0 when its document conforms,
its HTTP representation satisfies section 3, and it has the HTML discovery link
required by section 8.

A **consumer** conforms when it tolerates unknown extension fields, does not
invent missing values, and treats the file as metadata rather than operational
authority.

See [VALIDATION.md](VALIDATION.md) for a practical test checklist.
