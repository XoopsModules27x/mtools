# mtools Shared-Helper Architecture

`mtools` is a XOOPS module that also hosts shared helper classes for other
modules. Consumer modules should depend on the public contract here, not copy
their own `class/Common/` folder.

## Runtime Contract

Consumers load mtools through:

```php
require_once XOOPS_ROOT_PATH . '/modules/mtools/bootstrap.php';
```

The bootstrap registers `XoopsModules\Mtools\...`, defines
`MTOOLS_API_VERSION`, and exposes `XoopsModules\Mtools\Bootstrap` for runtime
checks.

Consumer manifests should declare:

```php
$modversion['min_modules'] = ['mtools' => '1.1.0'];
```

The consumer-facing entry point is `XoopsModules\Mtools\Module\ConsumerRuntime`.
It wraps the low-level `Bootstrap` primitive in one-call helpers:

- `ConsumerRuntime::dependencyError()` — returns `''` when ready, otherwise the
  message to display (used by the absence-safe `<dirname>_mtools_dependency_error()`
  shim).
- `ConsumerRuntime::guard(XOOPS_URL)` — at the top of public/admin entry points;
  redirects with the message and stops the request when mtools is missing.
- `ConsumerRuntime::assertReady($module)` — in install/update hooks; records the
  error on the module and returns `false`.

`Bootstrap::checkRuntime()` / `assertRuntime()` remain the low-level primitives
that `ConsumerRuntime` delegates to.

**requireActive policy.** `Bootstrap::checkRuntime(..., bool $requireActive = false)`
defaults to *installed + version-compatible* — it does **not** require mtools to be
ACTIVE. Helper classes load by file path through the autoloader regardless of
active state, so an inactive-but-installed mtools is a valid dependency. A consumer
that genuinely needs mtools active (e.g. for its preload-driven behavior) opts in
with `requireActive: true`.

The first executable contract test is
`tests/Contract/ConsumerContractTest.php`. It verifies the public mtools API
against an **in-repo fixture consumer** (`tests/Fixtures/Consumer/Utility.php`,
which extends `XoopsModules\Mtools\Common\SysUtility`, plus a sibling
`Helper.php`) rather than reaching into the sibling `quotes` module on disk — so
the suite passes whether or not any other module is checked out alongside mtools.

## Namespace Tiers

### Stable Shared API

`XoopsModules\Mtools\Common\...`

This tier is for helpers that consumer modules may extend or instantiate.
Semver applies: breaking changes require a major version bump and a deprecation
path. Not every class in `Common\` is the same *quality* of API, however, and
the eventual XMF extraction will be class-by-class — so the tier is split into
three support levels below. All three are stable to depend on; they differ in
how pure they are, whether they render/redirect, and how close they are to being
XMF-ready.

#### Pure / library-grade (strongest XMF candidates)

No request reads, no output, no redirects; filesystem/DB only when explicitly
called with caller-supplied paths. These are the first classes that should be
proposed for XMF once they have multiple real consumers.

- `Text` / `TextInterface` (pure string helper extracted from `SysUtility::truncateHtml`; the strongest single XMF candidate)
- `Db` / `DbInterface` (database helpers extracted from `SysUtility`; the data-access methods take an explicit `\XoopsMySQLDatabase` handle — no global state — and `blockAddCatSelect()` is a pure SQL-fragment builder)
- `PaginationState` / `PaginationStateInterface` (pure pagination math — `pageCount`/`currentPage`/`offset`/`rangeStart`/`rangeEnd`/`band`/`pages`/`limitClause`; split out of `Paginator`)
- `Resizer` / `ResizeRequest` / `ResizeResult` / `ImageResizerInterface` (pure GD image resizer — `resize(ResizeRequest): ResizeResult`, fit modes inside/cover/stretch, EXIF orientation, format-preserving; consolidates ~19 per-module copies; no `$_POST`/`$_FILES`/echo/redirect)
- `ModuleConfig` (typed, immutable `readonly` value object over `config/config.php`, built via `fromObject()`; returned by `Configurator::config()`)
- `Configurator`
- `VersionChecks`
- `DirectoryChecker`
- `FileChecker`
- `ModuleStats`
- `ObjectTree`
- `UpdateChecker`

#### Migration adapters (stable to extend, NOT XMF-ready as one unit)

Useful, stable, and the backbone of the dedup story — but broad, trait-heavy, or
mixed-purpose. Extend/instantiate them freely; do **not** assume any of them
graduates to XMF whole. When promoting, extract proven *methods* into focused
classes instead.

- `SysUtility` (back-compat **facade** — keeps `getInstance`/`prepareFolder`, the VersionChecks/ServerStats/FilesManagement traits, and forwards its former statics to `Text`/`Db`/`Output`; extend it freely, but new pure code should target `Text`/`Db` directly)
- `FilesManagement` (broad recursive copy/delete/move; prefer the `DirectoryChecker`/`FileChecker` allowed-base-path model for new code)
- `Migrate`

#### Admin-Legacy / output helpers (stable, but render or redirect)

These echo HTML, build forms, or redirect. They are fine for converted modules
and admin pages, but they are categorically different from the pure tier and are
**not** XMF candidates in their current form. Treat them as convenience, not
contract.

- `Output` (admin/UI output extracted from `SysUtility` — `selectSorting`/`getEditor`/`metaKeywords`/`metaDescription`; context-explicit, takes the consumer `Helper` as a parameter)
- `Blocksadmin` (echoes admin block-management UI)
- `Breadcrumb` (renders via Smarty)
- `LetterChoice` (renders alphabet picker markup)
- `ServerStats` (echoes a stats fieldset)
- `ModuleFeedback` (builds a XoopsThemeForm)
- `Confirm` (builds a "are you sure?" `XoopsThemeForm`; consolidates ~18 per-module copies. Takes the consumer module dir explicitly — `Confirm::forModule($helper, ...)` — so `_CO_<MODULE>_DELETE_*` constants resolve, fixing the copies' `basename(__DIR__)`→`"Common"` bug)
- `TestdataButtons` (redirects)
- `TestdataSample` (writes fixtures via TableLoad)
- `Paginator` (**`@deprecated`** legacy renderer — delegates its page math to the pure `PaginationState`; for new code prefer `PaginationState` plus the `render_pagination` Smarty plugin from `xoops/smartyextensions`)

## Standalone requirement: NO TadTools dependency

**mtools must be fully self-contained. It must not depend on the `tadtools`
module (or any other module) at runtime.** TadTools is an *inspiration* only
(see `docs/credits.txt`); specific ideas may be re-implemented natively inside
mtools, but mtools never calls into tadtools.

The hard runtime couplings to tadtools were inherited copy-paste residue from a
tadtools-derived scaffold and have been **removed** (they lived in the
module-local `XoopsModules\Mtools\Utility`, NOT in `Common\`, so consumers
extending `Common\SysUtility` were never affected):

| Former coupling | Resolution (done) |
| --- | --- |
| `Utility::getPageBar()` → `new \XoopsModules\Tadtools\PageBar(...)` | Rewritten onto the self-contained `Common\Paginator`. |
| `xoops_loadLanguage('main', 'tadtools')` in `web_error()` / `toolbar_bootstrap()` | Removed; labels now use `defined()`-guarded `_MA_MTOOLS_*` constants with English fallbacks. |
| `Utility::TadToolsXoopsModuleConfig()` (read the tadtools module's config) | Deleted (and its test stub). |
| qrcode/app block templates `include`-ing tadtools templates + image + `_MB_TT_*` | Re-homed: templates now include mtools' own `b4.tpl`/`b3/`/`b4/`, use `assets/images/app_qrcode.png`, and `_MB_MTOOLS_APP_*` constants. |

`Utility::mk_qrcode()` is self-contained (local `class/qrcode/qrcode.php`), so QR
*generation* was unaffected.

**Remaining cosmetic relic (not a runtime dependency):** the admin theme-config
template `templates/mtools_adm_index.tpl` still references tadtools-style
`_MA_TT_*` constants and `tt_*` field names. These are not defined in mtools and
do not load or call tadtools — the page simply shows blank labels. Renaming them
to `_MA_MTOOLS_*` (and reviewing whether the theme-config admin page belongs in a
helper host at all) is a follow-up; it does not affect mtools' standalone status.

### Experimental

`XoopsModules\Mtools\Lab\...`

This tier is for helpers that are not yet safe as public API. They may change
between minor releases. Current Lab classes:

- `DataMapperInterface`
- `IdentityMap`
- `IdentityMapInterface`
- `IdentityMapTrait`
- `Repository`
- `RepositoryInterface`

### mtools-Local

`XoopsModules\Mtools\...` without a shared sub-namespace is module-local unless
documented otherwise. Consumers should not extend these classes except for the
explicit contract classes:

- `Bootstrap`
- `Module\Dependency`
- `Module\ConsumerRuntime` (call its statics; do not extend)
- `Module\ModuleContext` (immutable per-module paths/URLs/uploads/config over `Xoops\Helpers\Service\Path`/`Url`/`Config`; `for($dir)`/`fromHelper($helper)`; `defineConstants()` reproduces the legacy `{UP}_*` constant block)
- `Module\Installer` (shared install/update filesystem boilerplate — `prepare()`/`install()` plus the granular `createUploadFolders`/`copyBlankFiles`/`copyTestFolders`/`dropModuleTables`/`purgeHtmlTemplates`/`removeOldAssets` steps)

## Adoption tooling

- `tools/scan-common.php` — read-only adoption scanner
  (`php tools/scan-common.php [dirname]`). It reports each consumer's bootstrap,
  `min_modules`, Utility-adapter shape, dependency shim, any local `Common\`
  copies (shadowing a shared class vs. local-only), `Configurator` no-arg traps,
  and tests, then prints a verdict (converted / in progress / not started / not a
  consumer). It never writes.
- `starters/consumer/` — copy-ready scaffold: `bootstrap.php`, the absence-safe
  `include/mtools_dependency.php` shim, a `class/Utility.php` adapter, and a
  `tests/Unit/ConsumerSmokeTest.php` smoke test.

## Side-Effect Rules

Shared classes must not:

- read browser request data at file scope
- redirect, echo, or exit at file scope
- write to the database during autoload
- inject theme assets during global preload execution
- perform filesystem writes from posted raw paths

UI helpers must accept the consuming module context explicitly when a template,
asset path, or module dirname is needed.

## XMF Graduation Gate

A helper can be proposed for XMF only after it has:

- at least one real consumer beyond the module that introduced it
- stable constructor and method signatures
- no module-relative assumptions
- no request or preload side effects
- documented behavior in this architecture file and class PHPDoc
- smoke, contract, and relevant integration tests

Current candidates (pure / multi-consumer, signatures stable enough to propose):

- `Text`
- `Db` (now that every method takes an explicit `\XoopsMySQLDatabase` handle)
- `PaginationState`
- `Resizer`
- `VersionChecks`
- `DirectoryChecker`
- `FileChecker`
- `Configurator` (after the `ModuleConfig` value object landed)
- `ConsumerRuntime` status objects

Keep in mtools (admin/UI/legacy — not XMF candidates in their current form):
`Output`, `Blocksadmin`, `Breadcrumb`, `LetterChoice`, `ModuleFeedback`, `Confirm`,
`ServerStats`, `TestdataButtons`/`TestdataSample`, and the current `Paginator`
renderer.
