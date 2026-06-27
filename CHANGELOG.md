# Changelog

All Notable changes to `:package_name` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## 1.2.0  [Unreleased]

Conversion-framework release: turns mtools from "a shared copy of Common classes" into a
thin incubation layer over `xoops/xmf` + `xoops/helpers`, with adoption primitives that cut
the per-module conversion tax. `quotes` is the reference consumer.

### Added
- **`Common\Text` / `Common\TextInterface`** — pure `truncateHtml()` extracted from `SysUtility`
  (the strongest XMF-graduation candidate). Side-effect free.
- **`Common\Db` / `Common\DbInterface`** — database helpers (`queryAndCheck`, `fieldExists`,
  `cloneRecord`, `enumerate`, …) that take an explicit `\XoopsMySQLDatabase` handle.
- **`Common\Output`** — admin/UI helpers (`selectSorting`, `getEditor`, `metaKeywords`,
  `metaDescription`) split out of `SysUtility`; context-explicit (consumer `Helper` passed in).
- **`Common\PaginationState` / `Common\PaginationStateInterface`** — pure pagination math
  (page count, offset, range, band, `LIMIT` clause) split out of `Paginator`. No `$_SERVER`/HTML/SQL.
- **`Common\Resizer` / `Common\ResizeRequest` / `Common\ResizeResult` / `Common\ImageResizerInterface`**
  — pure GD image resizer (`resize(ResizeRequest): ResizeResult`); fit modes inside/cover/stretch,
  EXIF orientation handling, format-preserving, quality default 85. No `$_POST`/`$_FILES`/echo/redirect.
  Consolidates ~19 per-module resizer copies.
- **`Common\ModuleConfig`** — typed, immutable `readonly` value object over `config/config.php`,
  built via `fromObject()` (null-safe coercion); returned by `Configurator::config()`.
- **`Common\Confirm`** — shared "are you sure?" delete-confirmation `XoopsThemeForm`, consolidating
  ~18 per-module copies. Takes the consumer module dir explicitly (`Confirm::forModule($helper, …)`)
  so `_CO_<MODULE>_DELETE_*` constants resolve; this fixes the copies' `basename(__DIR__)` bug, which
  resolved to the literal `"Common"` and left every dialog on the hardcoded English fallback.
- **`Module\ConsumerRuntime`** — one-call consumer runtime checks: `dependencyError()`,
  `isReady()`, `guard()` (entry points), `assertReady()` (install/update hooks).
- **`Module\ModuleContext`** — immutable per-module paths/URLs/uploads/config over
  `Xoops\Helpers\Service\Path/Url/Config`, plus `defineConstants()` to drop a module's
  `{UP}_*` constant block to one call.
- **`Module\Installer`** — shared install/update filesystem boilerplate (create upload folders,
  copy blank files / test data, drop tables, purge `.html` templates, remove old assets),
  collapsing ~50–100 lines per consumer `oninstall.php`/`onupdate.php`.
- **`Configurator::forModule(\Xmf\Module\Helper $helper)`** — named constructor.
- **`tools/scan-common.php`** — read-only adoption scanner (bootstrap / `min_modules` / Utility
  adapter / dependency shim / local `Common\` copies / Configurator no-arg traps / tests / verdict).
- **`starters/consumer/`** — copy-ready bootstrap, dependency shim, Utility adapter, and a
  **`tests/Unit/ConsumerSmokeTest.php`** template.
- **Contract test** now runs against an in-repo fixture (`tests/Fixtures/Consumer/`,
  `tests/Contract/ConsumerContractTest.php`) instead of the sibling `quotes` module on disk.

### Changed
- **BREAKING — `Configurator`:** the no-argument / empty-argument constructor now THROWS
  `InvalidArgumentException` instead of silently falling back to mtools' own `config/config.php`.
  Always pass the consumer base dir — use `Configurator::forModule($helper)` or
  `new Configurator($helper->path())`.
- `SysUtility` is now a back-compat **facade** that forwards to `Text`/`Db`/`Output`; consumer
  subclasses keep working unchanged.
- Consumer-awareness fix: `selectSorting()` / `getEditor()` resolve the **consumer's** `Helper`
  via late static binding (`consumerHelper()`), not mtools' own.
- `FilesManagement` is now a thin wrapper that delegates to `Xoops\Helpers\Utility\Filesystem`.
- `Paginator` delegates its page math to the pure `PaginationState` (it remains the legacy renderer).
- `Repository` / `IdentityMap*` moved from `Common\` to the experimental `Lab\` tier.
- Dependency policy clarified: a consumer requires mtools **installed + version-compatible**, not
  necessarily **active** (helper classes load by file path regardless of active state).
  `Bootstrap::checkRuntime()` / `assertRuntime()` now default `requireActive` to `false` and expose
  it as an opt-in parameter for modules that need an active-only mtools feature.
- Test gate: pre-existing auto-generated scaffold stubs are grouped `#[Group('legacy')]` and
  excluded from the default `composer test` run (run them with `composer test:legacy`), so a green
  default run reflects the 1.2.0 APIs. `@covers` annotations converted to `#[CoversClass]` attributes.

### Deprecated
- `Common\Paginator` — render pagination in templates with the `render_pagination` Smarty plugin
  (`xoops/smartyextensions`, Bootstrap 5) instead.
- `SysUtility::queryFAndCheck()` (use `queryAndCheck()` / `$db->exec()`), `SysUtility::tableExists()`
  (use `Xmf\Database\Tables`). `Db::*` with the global handle is back-compat only — new code injects
  a `\XoopsMySQLDatabase`.

### Removed
- TadTools runtime coupling: the `b3/` / `b4/` block-template variants and the `Tadtools\PageBar`
  dependency (pagination reimplemented natively as `Paginator`/`PaginationState`).
- Dead `image_max_width` / `image_max_height` module preferences (and their
  `_MI_MTOOLS_IMAGE_MAX_*` constants) — scaffold-generator boilerplate ("uploaded by the CKeditor")
  that nothing read. The `Resizer` takes explicit dimensions via `ResizeRequest`, so it never used them.

### Fixed
- mtools `composer test` is reliable again (bootstrap loads stubs in unit-only mode; stale
  `IdentityMap`/`Repository`/trait-mock scaffold tests fixed; `composer test` is self-contained
  via `vendor/bin/phpunit`).
- Docs: the conversion guide and consumer starter now build the `Configurator` in install/update
  hooks with `new Configurator(\dirname(__DIR__))` instead of `Configurator::forModule($helper)`.
  At install time the consumer module is not registered yet, so its Helper cannot resolve its own
  path — the previous guidance caused a "Missing config file" fatal on fresh installs.

## 1.1.0 RC1  [2026-05-18]

### Added
- Added `docs/GITHUB-WIKI.md` with an automated GitHub Wiki publishing workflow.
- Added `docs/USAGE.md` as a helper reference for consumer modules.

### Deprecated
- Nothing

### Fixed
- Fixed `Utility::get_bootstrap()` so a missing `mtools_setup` row no longer triggers "Cannot use bool as array" warnings.
- Fixed `MTOOLS_PATH` and `MTOOLS_URL` constant redefinition warnings when `bootstrap.php` and `include/common.php` are both loaded.
- Hardened `FileChecker` and `DirectoryChecker` path containment checks and removed `0777` from accepted permission modes.
- Made `ModuleFeedback` require a real consumer module context instead of silently falling back to `mtools`.

### Removed
- Nothing

### Security
- Nothing
