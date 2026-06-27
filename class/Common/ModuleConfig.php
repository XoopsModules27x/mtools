<?php declare(strict_types=1);

namespace XoopsModules\Mtools\Common;

/**
 * Typed, immutable view of a module's `config/config.php`.
 *
 * Replaces the untyped `stdClass` that {@see Configurator} used to read field-by-field
 * (which warned on any missing property). {@see self::fromObject()} coerces and defaults
 * every field, so a partial config no longer emits undefined-property notices, and
 * consumers get a real contract: `$configurator->config()->uploadFolders` is always a
 * typed array.
 *
 * @api Stable Common-tier API (Lab\* is experimental, module-local code is private).
 * @since 1.2.0
 */
final class ModuleConfig
{
    /**
     * @param array<int|string, mixed> $uploadFolders
     * @param array<int|string, mixed> $copyBlankFiles
     * @param array<int|string, mixed> $copyTestFolders
     * @param array<int|string, mixed> $templateFolders
     * @param array<int|string, mixed> $oldFiles
     * @param array<int|string, mixed> $oldFolders
     * @param array<int|string, mixed> $renameTables
     * @param array<int|string, mixed> $renameColumns
     * @param array<int|string, mixed> $moduleStats
     */
    public function __construct(
        public readonly string $name = '',
        public readonly array $uploadFolders = [],
        public readonly array $copyBlankFiles = [],
        public readonly array $copyTestFolders = [],
        public readonly array $templateFolders = [],
        public readonly array $oldFiles = [],
        public readonly array $oldFolders = [],
        public readonly array $renameTables = [],
        public readonly array $renameColumns = [],
        public readonly array $moduleStats = [],
        public readonly string $modCopyright = '',
    ) {}

    /**
     * Build from the raw object returned by `config/config.php`, coercing types and
     * defaulting any field the module omitted.
     */
    public static function fromObject(object $config): self
    {
        return new self(
            name: (string) ($config->name ?? ''),
            uploadFolders: (array) ($config->uploadFolders ?? []),
            copyBlankFiles: (array) ($config->copyBlankFiles ?? []),
            copyTestFolders: (array) ($config->copyTestFolders ?? []),
            templateFolders: (array) ($config->templateFolders ?? []),
            oldFiles: (array) ($config->oldFiles ?? []),
            oldFolders: (array) ($config->oldFolders ?? []),
            renameTables: (array) ($config->renameTables ?? []),
            renameColumns: (array) ($config->renameColumns ?? []),
            moduleStats: (array) ($config->moduleStats ?? []),
            modCopyright: (string) ($config->modCopyright ?? ''),
        );
    }
}
