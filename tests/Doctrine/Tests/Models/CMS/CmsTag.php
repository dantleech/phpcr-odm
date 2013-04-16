<?php

namespace Doctrine\Tests\Models\CMS;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Doctrine\ODM\PHPCR\DocumentRepository;
use Doctrine\ODM\PHPCR\Id\RepositoryIdInterface;

/**
 * @PHPCRODM\Document(referenceable=true)
 */
class CmsTag
{
    /** @PHPCRODM\Id(strategy="parent") */
    public $id;

    /** @PHPCRODM\NodeName */
    public $name;

    /** @PHPCRODM\ParentDocument() */
    public $parent;

    /** @PHPCRODM\ReferenceMany() */
    public $references;

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function addDocumentReference($doc)
    {
        $this->references[] = $doc;
    }
}
