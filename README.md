![alt XOOPS CMS](https://xoops.org/images/logo.png)
## content module for  [XOOPS CMS 2.7.0+](https://xoops.org)
[![XOOPS CMS Module](https://img.shields.io/badge/XOOPS%20CMS-Module-blue.svg)](https://xoops.org)
[![Software License](https://img.shields.io/badge/license-GPL-brightgreen.svg?style=flat)](https://www.gnu.org/licenses/gpl-2.0.html)

[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/XoopsModules25x/mtools.svg?style=flat)](https://scrutinizer-ci.com/g/XoopsModules25x/mtools/?branch=master)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/95b12220e0ac4056b9af52af708379c9)](https://www.codacy.com/app/XoopsModules25x/mtools)
[![Code Climate](https://img.shields.io/codeclimate/github/XoopsModules25x/mtools.svg?style=flat)](https://codeclimate.com/github/XoopsModules25x/mtools)
[![Latest Pre-Release](https://img.shields.io/github/tag/XoopsModules25x/mtools.svg?style=flat)](https://github.com/XoopsModules25x/mtools/tags/)
[![Latest Version](https://img.shields.io/github/release/XoopsModules25x/mtools.svg?style=flat)](https://github.com/XoopsModules25x/mtools/releases/)

**mtools** is a XOOPS helper-host module. It provides a stable shared-helper
layer for modules such as `quotes`, so reusable module support code can live in
one vetted place before graduating to XMF.

Consumer modules should load `modules/mtools/bootstrap.php`, declare
`$modversion['min_modules'] = ['mtools' => '1.1.0']`, and extend stable helpers
from `XoopsModules\Mtools\Common\...`.

Current runtime contract:

- `bootstrap.php` is the public consumer entry point.
- `preloads/core.php` only registers the shared bootstrap and must not inject
  assets or write setup data on every request.
- `include/common.php` defines module constants idempotently so repeated
  bootstrap/common loading is safe on PHP 8.2+ and PHP 9.
- Consumer-facing docs can be synced to GitHub Wiki from the canonical Markdown
  files in `docs/`.

Architecture and adoption docs:

- [Shared-helper architecture](docs/architecture.md)
- [Helper usage reference](docs/USAGE.md)
- [Consumer guide](docs/CONSUMER-GUIDE.md)
- [Conversion tutorial](docs/CONVERTING-A-MODULE-WITH-MTOOLS.md)
- [Adoption matrix](docs/ADOPTION.md)
- [Consumer starter files](starters/consumer/README.md)
- [GitHub Wiki publishing](docs/GITHUB-WIKI.md)
- [Versioning and deprecation](docs/VERSIONING.md)

[![Tutorial Available](https://xoops.org/images/tutorial-available-blue.svg)](https://app.gitbook.com/@xoops/s/mtools-tutorial/) Tutorial: see [GitBook](https://app.gitbook.com/@xoops/s/mtools-tutorial/).
To contribute to the Tutorial, [fork it on GitHub](https://github.com/XoopsDocs/mtools-tutorial)

[![Translations on Transifex](https://xoops.org/images/translations-transifex-blue.svg)](https://www.transifex.com/xoops)

Please visit us on https://xoops.org

Current and upcoming "next generation" versions of XOOPS CMS are crafted on GitHub at: https://github.com/XOOPS
