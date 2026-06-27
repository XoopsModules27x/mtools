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
 * Experimental repository bridge. Keep consumers on Common until this has tests.
 *
 * @internal This class is unstable and not part of the public Mtools API. It may change
 *           or be removed without notice. Consume {@see \XoopsModules\Mtools\Common} instead.
 */
class Repository extends \XoopsPersistableObjectHandler implements RepositoryInterface
{
    private IdentityMap $identityMap;

    /**
     * @param \XoopsDatabase|null $db             Database connection passed to the parent handler.
     * @param string              $table          Table name for the parent handler.
     * @param string              $className      Object class name for the parent handler.
     * @param string              $keyName        Primary key field for the parent handler.
     * @param string              $identifierName Identifier field for the parent handler.
     */
    public function __construct(?\XoopsDatabase $db = null, $table = '', $className = '', $keyName = '', $identifierName = '')
    {
        parent::__construct($db, $table, $className, $keyName, $identifierName);
        // Initialise the identity map so load() never dereferences an uninitialised typed property.
        $this->identityMap = new IdentityMap();
    }

    public function load($entity)
    {
        $entity = ucfirst((string)$entity) . 'Mapper';

        if ($this->identityMap->hasId($entity)) {
            return $this->identityMap->getObject($entity);
        }

        $this->identityMap->set($entity, new $entity($this->db));

        return $this->identityMap->getObject($entity);
    }
}

