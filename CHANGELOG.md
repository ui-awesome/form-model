# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## 0.2.1 Under development

## 0.2.0 July 20, 2026

- feat(model): modernize the package API and documentation, including consistency improvements, structural cleanup, and migration guidance in `UPGRADE.md` (@terabytesoftw).
- feat(model): simplify the form-model field API across public contracts, implementation, tests, and docs (@terabytesoftw).
- fix(model): rename `AbstractFormModel` to `BaseFormModel` across source, tests, and documentation; see `UPGRADE.md` for migration steps (@terabytesoftw).
- fix(model): remove `FieldMetadata` and move dot-notation metadata resolution directly into `BaseFormModel` getter methods (@terabytesoftw).
- feat(attribute): add property attributes (`Label`, `Hint`, `Placeholder`, `FieldConfig`) and integrate attribute-first metadata resolution with map fallback in `BaseFormModel` (@terabytesoftw).
- chore: update dependencies and configuration files and remove copyright and license comments from files.
- chore: replace manual metadata synchronization with scaffold-managed configuration, migrate CI from Super-Linter to quality and security workflows, and refresh README badges.

## 0.1.0 March 18, 2024

- chore: initial release.
