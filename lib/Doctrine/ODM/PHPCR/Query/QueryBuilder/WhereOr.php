<?php

namespace Doctrine\ODM\PHPCR\Query\QueryBuilder;

/**
 * Append an additional "where" with an OR
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class WhereOr extends Where
{
    public function getNodeType()
    {
        return self::NT_WHERE;
    }
}