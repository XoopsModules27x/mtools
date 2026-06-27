# mTools consumer starter files

These files are copy-ready templates for a module that wants to consume
`mtools` instead of keeping a private `class/Common` helper copy.

Replace `mymodule`, `Mymodule`, and `MYMODULE` with the consumer module's
dirname, namespace segment, and uppercase prefix.

Recommended copy targets:

- `bootstrap.php` -> `{consumer}/bootstrap.php`
- `include/mtools_dependency.php` -> `{consumer}/include/mtools_dependency.php`
- `class/Utility.php` -> `{consumer}/class/Utility.php`
- `tests/Unit/ConsumerSmokeTest.php` -> `{consumer}/tests/Unit/ConsumerSmokeTest.php`

After copying:

1. Add `'min_modules' => ['mtools' => '1.1.0']` to `xoops_version.php`.
2. Load `bootstrap.php` from install/update hooks, admin bootstrap, public entry points, and blocks before using mTools classes.
3. Check `mymodule_mtools_dependency_error()` before instantiating classes that extend `XoopsModules\Mtools\Common`.

## Smoke test

`tests/Unit/ConsumerSmokeTest.php` is a copy-ready PHPUnit test that verifies the
conversion stuck: the module bootstrap loads, the dependency shim returns a sane string,
and the local `Utility` adapter extends `XoopsModules\Mtools\Common\SysUtility`. After
copying, replace `Mymodule`/`mymodule` with your namespace segment / dirname and run
`composer test`. The SysUtility-extension assertion self-skips when mtools is not
reachable in a bare unit run (it is exercised in integration / on the live site).

## Runtime guards: `ConsumerRuntime`

All the version-check and message logic now lives in
`XoopsModules\Mtools\Module\ConsumerRuntime`, so the per-module
`mymodule_mtools_dependency_error()` shim is a thin, mtools-absence-safe wrapper
(the only consumer-side boilerplate left). Once `bootstrap.php` has run, call the
shared guard directly for each context:

```php
// Public / admin entry point (XOOPS loaded): redirect + stop on failure.
\XoopsModules\Mtools\Module\ConsumerRuntime::guard(XOOPS_URL);

// Install / update hook: record the error on the module and bail.
if (!\XoopsModules\Mtools\Module\ConsumerRuntime::assertReady($module)) {
    return false;
}

// Block (degrade to empty output): boolean form.
if (!\XoopsModules\Mtools\Module\ConsumerRuntime::isReady()) {
    return ['items' => []];
}
```

`dependencyError()` / `isReady()` / `guard()` / `assertReady()` all accept optional
`($minimumApiVersion, $minimumModuleVersion)` arguments (default `1.0.0` / `1.1.0`).

## Configurator

At runtime (admin pages, blocks, entry points) build the Configurator from the
consumer Helper. In install/update hooks the module is not registered yet, so the
Helper cannot resolve its path — build it from the module dir on disk instead.
Never call it with no argument (that path throws):

```php
// Runtime (module is active):
$configurator = \XoopsModules\Mtools\Common\Configurator::forModule($helper);
// equivalent to: new \XoopsModules\Mtools\Common\Configurator($helper->path());

// Install/update hooks (module not registered yet):
$configurator = new \XoopsModules\Mtools\Common\Configurator(\dirname(__DIR__));
```

