# Changelog

## 0.2.0 Under development

- Enh #2: Modernized the package API and documentation, including consistency improvements, structural cleanup, and migration guidance in `UPGRADE.md` (@terabytesoftw)
- Enh #3: Simplified the form-model field API across public contracts, implementation, tests, and docs (@terabytesoftw)
- Bug #4: Renamed `AbstractFormModel` to `BaseFormModel` across source, tests, and documentation; see `UPGRADE.md` for migration steps (@terabytesoftw)
- Bug #5: Removed `FieldMetadata` and moved dot-notation metadata resolution directly into `BaseFormModel` getter methods (@terabytesoftw)
- Enh #6: Added property attributes (`Label`, `Hint`, `Placeholder`, `FieldConfig`) and integrated attribute-first metadata resolution with map fallback in `BaseFormModel` (@terabytesoftw)

## 0.1.0 March 18, 2024

- Initial release
