# Consuming mtools From a Module

`quotes` is the reference consumer.

For a full conversion walkthrough, see
[`CONVERTING-A-MODULE-WITH-MTOOLS.md`](CONVERTING-A-MODULE-WITH-MTOOLS.md).

## Manifest

Add a module dependency:

```php
$modversion['min_modules'] = ['mtools' => '1.1.0'];
```

## Module Bootstrap

Create a module-level `bootstrap.php`:

```php
require_once __DIR__ . '/preloads/autoloader.php';

if (defined('XOOPS_ROOT_PATH')) {
    $mtoolsBootstrap = XOOPS_ROOT_PATH . '/modules/mtools/bootstrap.php';
    if (is_file($mtoolsBootstrap)) {
        require_once $mtoolsBootstrap;
    }
}
```

Class files should not require mtools internals. Entry points, install hooks,
CLI scripts, and tests load the module bootstrap instead.

## Dependency Check

All the version-check and message logic now lives in
`\XoopsModules\Mtools\Module\ConsumerRuntime`. A consumer keeps just ONE tiny
absence-safe shim — `include/<dirname>_mtools_dependency.php` — that guards the
case where mtools is *entirely absent* (a class inside mtools cannot report its
own non-existence) and otherwise delegates to `ConsumerRuntime`:

```php
use XoopsModules\Mtools\Module\ConsumerRuntime;

if (!function_exists('quotes_mtools_dependency_error')) {
    function quotes_mtools_dependency_error(): string
    {
        if (!class_exists(ConsumerRuntime::class)) {
            return 'The mtools module files are missing. Install mtools before installing or running Quotes.';
        }

        return ConsumerRuntime::dependencyError('1.0.0', '1.1.0');
    }
}
```

Under the hood `ConsumerRuntime::dependencyError()` wraps the low-level primitive
`\XoopsModules\Mtools\Bootstrap::checkRuntime()` + `Bootstrap::statusMessage()` —
consumers should not call those directly anymore.

### Active vs installed

**mtools only needs to be INSTALLED and version-compatible — it does NOT need to be
active.** Your `bootstrap.php` loads mtools' helper classes directly by file path, so they
work whether mtools is active or not; the dependency check therefore accepts an
installed-but-inactive mtools (matching XOOPS's own `min_modules` semantics). If your
module additionally relies on a feature that only exists while mtools is active, opt in
with `Bootstrap::checkRuntime($api, $version, requireActive: true)`.

Once `bootstrap.php` has run and mtools' presence is therefore established, use
the shared guards directly for each context:

```php
// Public / admin entry point (XOOPS loaded): redirect + stop on failure.
ConsumerRuntime::guard(XOOPS_URL);

// Install / update hook: record the error on the module and bail.
if (!ConsumerRuntime::assertReady($module)) {
    return false;
}
```

`guard()` / `assertReady()` assume mtools' files are present (the bootstrap shim
already handled total absence). The absence-safe `<dirname>_mtools_dependency_error()`
shim is what entry points and hooks call first; `guard()`/`assertReady()` are for
contexts where presence is already established.

## Reusing Helpers

Prefer thin extension classes in the consumer:

```php
namespace XoopsModules\Quotes;

use XoopsModules\Mtools;

final class Utility extends Mtools\Common\SysUtility
{
}
```

Do not create a local `class/Common/` copy unless the helper is genuinely
module-specific.
