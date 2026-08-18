# Repository instructions

When changing this repository:

- Read `SPECIFICATIONS.md` before changing the template or integration.
- Keep specification requirements separate from implementation guidance.
- Preserve the platform-neutral core; integrations belong under `integrations/`.
- Treat `AI.md` as a generic template, never as a real author's catalogue.
- Do not add inferred or scraped creative metadata to examples.
- Update the specification version only according to section 6.
- Keep all example domains under `example.com` or another reserved example
  domain.
- Run `./scripts/validate-repository.sh` before publishing.
- Validate PHP integrations with `php -l` when PHP is available.
- Check Markdown links before publishing.
- Use a reviewable branch and describe conformance-impacting changes clearly.
