<?php declare(strict_types=1);

namespace XoopsModules\Mtools\Lab;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.
*/

/**
 * @copyright 2000-2026 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author    XOOPS Development Team
 */

/**
 * IdentityMap
 *
 * @internal Experimental Lab tier — unstable, not part of the public mtools API. Use Common\* instead.
 */
final class IdentityMap implements IdentityMapInterface
{
    protected \ArrayObject $idToObject;
    protected \SplObjectStorage $objectToId;

    public function __construct()
    {
        $this->objectToId = new \SplObjectStorage();
        $this->idToObject = new \ArrayObject();
    }

    public function set($id, $object): void
    {
        $this->idToObject[$id]     = $object;
        $this->objectToId[$object] = $id;
    }

    public function getId($object)
    {
        if (!$this->hasObject($object)) {
            throw new \OutOfBoundsException();
        }

        return $this->objectToId[$object];
    }

    public function hasId($id): bool
    {
        return isset($this->idToObject[$id]);
    }

    public function hasObject($object): bool
    {
        return isset($this->objectToId[$object]);
    }

    public function getObject($id): object
    {
        if (!$this->hasId($id)) {
            throw new \OutOfBoundsException();
        }

        return $this->idToObject[$id];
    }
}

