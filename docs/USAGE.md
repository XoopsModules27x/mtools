# mTools Shared Helper Usage

This document is the quick reference for helpers that consumer modules may use.
For a full migration walkthrough, see
[`CONVERTING-A-MODULE-WITH-MTOOLS.md`](CONVERTING-A-MODULE-WITH-MTOOLS.md).

## Runtime Bootstrap

Consumer modules should load:

```php
require_once XOOPS_ROOT_PATH . '/modules/mtools/bootstrap.php';
```

The bootstrap registers the `XoopsModules\Mtools\...` namespace. The
consumer-facing entry point is `Module\ConsumerRuntime` (it wraps the low-level
`Bootstrap` primitive).

The recommended shape is an absence-safe shim plus two guard sites. Ship a
`<dirname>_mtools_dependency_error()` helper that survives mtools being missing
entirely, delegating to `ConsumerRuntime` when the class is present:

```php
// include/mtools_dependency.php (loaded from bootstrap.php)
function quotes_mtools_dependency_error(): string
{
    if (!class_exists(\XoopsModules\Mtools\Module\ConsumerRuntime::class)) {
        return 'This module requires the mtools module to be installed.';
    }

    return \XoopsModules\Mtools\Module\ConsumerRuntime::dependencyError();
}
```

At public/admin entry points (where XOOPS is loaded), guard and degrade:

```php
\XoopsModules\Mtools\Module\ConsumerRuntime::guard(XOOPS_URL);
```

In install/update hooks, assert readiness against the `$module`:

```php
if (!\XoopsModules\Mtools\Module\ConsumerRuntime::assertReady($module)) {
    return false;
}
```

Use these in install hooks, update hooks, admin bootstrap files, block files, CLI
scripts, and tests. Do not require files from `mtools/preloads/` directly.

`Bootstrap::checkRuntime()` / `assertRuntime()` remain available as the low-level
primitives if you need the raw status array; both accept a trailing
`bool $requireActive = false` (the default treats installed + version-compatible
as ready — see `ARCHITECTURE.md`).

## Stable Shared API

The stable shared API is under:

```php
XoopsModules\Mtools\Common
```

Only these classes are intended for normal consumer use.

### SysUtility

Purpose: shared XOOPS utility methods, including local XOOPS/PHP requirement
checks and filesystem setup helpers.

`SysUtility` is now a back-compat **facade**: its former statics forward to the
focused `Common\Text`, `Common\Db`, and `Common\Output` classes (keeping their
original signatures). Consumer subclasses keep extending it unchanged. Its
consumer-aware methods — `selectSorting()` and `getEditor()` — resolve the
**consumer's** `Helper` via late static binding (`consumerHelper()`), not
mtools' own. New pure code should target `Text`/`Db` directly.

For pagination, `Common\Paginator` is **deprecated**: it now delegates its page
math to the pure `Common\PaginationState` and remains only as a legacy renderer.
Prefer `PaginationState` for the math and the `render_pagination` Smarty plugin
(from `xoops/smartyextensions`) for the markup.

Typical consumer adapter:

```php
namespace XoopsModules\Quotes;

use XoopsModules\Mtools;

class Utility extends Mtools\Common\SysUtility
{
}
```

Use from install/update hooks:

```php
$utility = new \XoopsModules\Quotes\Utility();
$xoopsOk = $utility::checkVerXoops($module);
$phpOk   = $utility::checkVerPhp($module);
```

Stable since: `1.0.0`

XMF target: candidate after more consumer coverage.

### Configurator

Purpose: load module-local `config/config.php`, `config/icons.php`, and
`config/paths.php` into a typed shared object.

Build it with the named constructor `forModule()`, which derives the consumer
base directory from the `Helper`:

```php
$configurator = \XoopsModules\Mtools\Common\Configurator::forModule($helper);

foreach ($configurator->uploadFolders as $folder) {
    $utility::prepareFolder($folder);
}
```

The no-argument / empty constructor now **throws** `InvalidArgumentException`
instead of silently defaulting to mtools' own directory (the old footgun). Pass
the consumer path explicitly, or use `forModule($helper)`.

`Configurator::config()` returns a typed, immutable `Common\ModuleConfig` value
object (built via `ModuleConfig::fromObject()`) — prefer it over reading the raw
`stdClass` when you need a real contract.

The consumer module still owns the config files; mTools only provides the
reader contract.

Stable since: `1.0.0` — `forModule()` / `config()` since `1.2.0`

### ModuleContext

Purpose: an immutable per-module view of paths, URLs, the upload/images folders,
and config, built over `Xoops\Helpers\Service\Path`/`Url`/`Config`.

Construct it for a dirname or from a `Helper`, then read what you need:

```php
$ctx = \XoopsModules\Mtools\Module\ModuleContext::fromHelper($helper);
// or: ModuleContext::for('quotes');

$ctx->path('admin');        // filesystem path, optional sub-path
$ctx->uploadPath();         // upload folder path
$ctx->url('index.php');     // public URL
$ctx->adminUrl();           // admin URL
$ctx->imagesUrl();          // assets/images URL
$ctx->config('key');        // a module config value
```

It can also reproduce the legacy `{UPPER}_*` constant block (e.g.
`QUOTES_PATH`, `QUOTES_URL`, `QUOTES_UPLOAD_PATH`) for code that still reads
those constants:

```php
$ctx->defineConstants();
```

Stable since: `1.2.0`

### Module\Installer

Purpose: the shared install/update filesystem boilerplate, so a module's
`oninstall.php` / `onupdate.php` shrink to one or two calls.

```php
// pre-install: create folders + drop the module's own tables
\XoopsModules\Mtools\Module\Installer::prepare($module, $configurator);

// install: folders + blank index files + test data + purge stray .html templates
\XoopsModules\Mtools\Module\Installer::install($module, $configurator);
```

The granular steps are also public if a hook needs only part of the work:
`createUploadFolders()`, `copyBlankFiles()`, `copyTestFolders()`,
`dropModuleTables()`, `purgeHtmlTemplates()`, `removeOldAssets()` (each takes the
`$module` and/or the `Configurator`).

Stable since: `1.2.0`

### Resizer

Purpose: a pure GD image resizer that replaces the ~19 near-identical per-module
copies. It reads no request/upload superglobals, echoes nothing, and redirects
nowhere — you hand it a request value object and get a result value object back.

```php
use XoopsModules\Mtools\Common\Resizer;
use XoopsModules\Mtools\Common\ResizeRequest;

$result = (new Resizer())->resize(new ResizeRequest(
    sourcePath: $source,
    targetPath: $target,
    maxWidth:   800,
    maxHeight:  600,
    fit:        ResizeRequest::FIT_INSIDE,   // FIT_INSIDE | FIT_COVER | FIT_STRETCH
    quality:    85,
));

if ($result->ok) {
    // $result->targetPath, $result->width, $result->height
} else {
    // $result->error
}
```

It honours EXIF orientation and preserves the source image format.

Stable since: `1.2.0`

### VersionChecks

Purpose: pure local XOOPS/PHP requirement checks.

Remote GitHub release polling is intentionally not part of this trait. Use
`UpdateChecker` explicitly if a module needs optional remote update checks.

Stable since: `1.0.0`

### UpdateChecker

Purpose: optional remote module update checks against GitHub releases.

This class performs network I/O. Do not call it from foundational install or
page-bootstrap paths.

Stable since: `1.0.0`

### Breadcrumb

Purpose: render a simple breadcrumb using a consumer-provided module context.

Pass the consumer dirname or template explicitly:

```php
$breadcrumb = new \XoopsModules\Mtools\Common\Breadcrumb('quotes', 'quotes_common_breadcrumb.tpl');
```

Do not rely on the helper guessing the consumer module.

Stable since: `1.0.0`

### DirectoryChecker and FileChecker

Purpose: check and manage known module filesystem paths.

State-changing methods require containment under a known base path or an
explicit allowed base path. Defaults are conservative: directories `0755`,
files `0644`.

```php
\XoopsModules\Mtools\Common\DirectoryChecker::createDirectory($target, 0755, $allowedBasePath);
\XoopsModules\Mtools\Common\FileChecker::copyFile($source, $target, $allowedBasePath);
```

Do not feed raw browser-posted paths into these helpers.

Stable since: `1.0.0`

### Blocksadmin

Purpose: shared legacy block-admin rendering and saving behavior.

This helper still contains UI-era behavior and should be used carefully. It is
stable for legacy consumers, but it is not a model for new library-style helper
design.

Stable since: `1.0.0`

### ModuleFeedback, ModuleStats, LetterChoice, ObjectTree, ServerStats, Migrate, TestdataButtons, TestdataSample, FilesManagement

Purpose: legacy helper support for existing modules.

These helpers are available under `Common\...`, but each should be adopted only
after checking that it fits the consumer module and does not pull UI or request
behavior into library code unnecessarily.

Stable since: `1.0.0`

## Experimental API

Experimental helpers live under:

```php
XoopsModules\Mtools\Lab
```

Current Lab classes:

- `DataMapperInterface`
- `IdentityMap`
- `IdentityMapInterface`
- `IdentityMapTrait`
- `Repository`
- `RepositoryInterface`

Do not use these in broad module migrations yet. They need real consumer
coverage and tests before they can graduate to `Common`.

## Consumer Rule of Thumb

Use mTools for shared infrastructure. Keep module behavior in the module.

Good mTools candidates:

- dependency checks
- install/update support
- generic config loading
- generic local version checks
- safe filesystem setup helpers

Keep local:

- object and handler classes
- SQL schema
- admin controllers
- public controllers
- templates and block templates
- CSS and JavaScript
- module-specific business rules

