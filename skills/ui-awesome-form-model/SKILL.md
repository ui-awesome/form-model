---
name: ui-awesome-form-model
description: Use when defining labels, hints, placeholders, field configuration, nested field paths or field-scoped errors with ui-awesome/form-model in PHP applications. Not for running validation rules, Yii ActiveRecord or database models, widget rendering or frontend assets.
license: BSD-3-Clause
---

# Work with form metadata and field errors

## Resolve the actual package

Inspect the installed package path, reference and PHP constraint before choosing an API. This checkout currently requires
PHP ^8.3. Compare Composer installed metadata, the lockfile and actual source; modified/path repositories
may differ from a published revision. A skill installation does not install runtime dependencies.

Start with `src/BaseFormModel.php` and only the related source needed for the task. Composer archives exclude `docs/` and
`tests/`: in a vendor install, read the installed source and PHPDoc, and use matching-revision repository documentation
or tests only after confirming the revision, not an assumed latest API.
Do not use this package as a substitute for unrelated Yii widgets, database models or frontend asset management.

## Package-specific workflow

- Extend `BaseFormModel` for field metadata and errors; it builds on the installed `ui-awesome/model` package, not Yii
  ActiveRecord. `FormModelInterface` is the public contract; `FieldError` is the error store behind it and can be used
  on its own.

- Inspect `getLabel()`, `getHint()`, `getPlaceholder()` and `getFieldConfig()` alongside the `Attribute\Label`,
  `Attribute\Hint`, `Attribute\Placeholder` and `Attribute\FieldConfig` classes.

- `addError()` appends to a field. Use `getFirstError()`, `getErrorSummary()` and `clearError()` for their distinct
  contracts. `getRules()` and `getRule()` only expose rules; do not claim this package executes a validation engine
  merely because rules are present.

## Preserve package contracts

These behaviors are intended. Do not "fix" them in an unrelated task:

- A property attribute wins over the map method: `#[Label]` overrides `getLabels()`, and likewise for hints,
  placeholders and field configuration.
- A field without a label gets a generated title-case label (`first_name` becomes `First Name`); hints and placeholders
  without a value return `''`.
- Dot notation delegates to a nested `FormModelInterface` value. When the parent property is `null` or not a form
  model, the full dotted name resolves locally (`getLabel('addr.city')` returns `Addr.city`). An undefined parent
  property or an empty segment (`addr.`) throws `InvalidArgumentException`.
- `getRule()` returns `null` for a rule that is not an array; `getRules()` may be a generator.
- `isValidated()` is `true` only for a field present in the error map with no messages, as after
  `setErrors(['email' => []])`; a field never touched is not validated. `setErrors()` replaces the whole collection.
- `getErrorSummary()` returns a flat list; with `$first = true` it returns one message per field, keyed by field name.
- Cloning preserves existing errors and validation state. Later additions, clearing or replacement on either instance
  stay independent, including subclasses with their own `__clone()` hook. This isolates field errors, not arbitrary
  nested object properties.

## Verify the result

Execute focused examples against the inspected package and its actual dependencies. Assert normal, boundary and negative
cases with explicit failures; do not rely on disabled PHP assertions. Preserve supported PHP versions and report missing
runtime/tooling rather than changing constraints to manufacture a pass. Report what was executed separately from what was
only read. Do not install dependencies or modify unrelated files without an authorized task.
