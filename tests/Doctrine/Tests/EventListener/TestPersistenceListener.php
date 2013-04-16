<?php

namespace Doctrine\Tests\EventListener;

use Doctrine\ODM\PHPCR\Event\PostFlushEventArgs;
use Doctrine\ODM\PHPCR\Event\PreFlushEventArgs;
use Doctrine\Common\EventArgs;
use Doctrine\Tests\Models\CMS\CmsPage;
use Doctrine\Tests\Models\CMS\CmsItem;

class TestPersistenceListener
{
    public $pagePrePersist = false;
    public $pagePostPersist = false;
    public $itemPrePersist = false;
    public $itemPostPersist = false;
    public $preUpdate = false;
    public $postUpdate = false;
    public $pagePreRemove = false;
    public $pagePostRemove = false;
    public $itemPreRemove = false;
    public $itemPostRemove = false;
    public $onFlush = false;
    public $postFlush = false;
    public $preFlush = false;
    public $itemPreMove = false;
    public $itemPostMove = false;
    public $pagePreMove = false;
    public $pagePostMove = false;

    public function prePersist(EventArgs $e)
    {
        $document = $e->getDocument();
        if ($document instanceof CmsPage){
            $this->pagePrePersist = true;
        } elseif ($document instanceof CmsItem){
            $this->itemPrePersist = true;
        }
    }

    public function postPersist(EventArgs $e)
    {
        $document = $e->getDocument();
        if ($document instanceof CmsPage){
            $this->pagePostPersist = true;
        } elseif ($document instanceof CmsItem){
            $this->itemPostPersist = true;
        }
    }

    public function preUpdate(EventArgs $e)
    {
        $document = $e->getDocument();
        if (! $document instanceof CmsPage ){
            return;
        }
        $dm = $e->getDocumentManager();

        foreach ($document->getItems() as $item) {
            $dm->persist($item);
        }
        $this->preUpdate = true;
    }

    public function postUpdate(EventArgs $e)
    {
        $this->postUpdate = true;
    }

    public function preRemove(EventArgs $e)
    {
        $document = $e->getDocument();
        if ($document instanceof CmsPage){
            $this->pagePreRemove = true;
        } elseif ($document instanceof CmsItem){
            $this->itemPreRemove = true;
        }
    }

    public function postRemove(EventArgs $e)
    {
        $document = $e->getDocument();
        if ($document instanceof CmsPage){
            $this->pagePostRemove = true;
        } elseif ($document instanceof CmsItem){
            $this->itemPostRemove = true;
        }
    }

    public function preMove(EventArgs $e)
    {
        $document = $e->getDocument();
        if ($document instanceof CmsPage){
            $this->pagePreMove = true;
        } elseif ($document instanceof CmsItem){
            $this->itemPreMove = true;
        }
    }

    public function postMove(EventArgs $e)
    {
        $document = $e->getDocument();
        if ($document instanceof CmsPage){
            $this->pagePostMove = true;
        } elseif ($document instanceof CmsItem){
            $this->itemPostMove = true;
        }
    }

    public function onFlush(EventArgs $e)
    {
        $this->onFlush = true;
    }

    public function postFlush(PostFlushEventArgs $e)
    {
        $this->postFlush = true;
    }

    public function preFlush(PreFlushEventArgs $e)
    {
        $this->preFlush = true;
    }
}

