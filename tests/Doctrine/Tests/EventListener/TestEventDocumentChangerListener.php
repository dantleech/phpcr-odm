<?php

namespace Doctrine\Tests\EventListener;

use Doctrine\Common\EventArgs;

class TestEventDocumentChangerListener
{
    public $prePersist = false;
    public $postPersist = false;
    public $preUpdate = false;
    public $postUpdate = false;
    public $preRemove = false;
    public $postRemove = false;
    public $preMove = false;
    public $postMove = false;
    public $onFlush = false;

    public function prePersist(EventArgs $e)
    {
        $document = $e->getDocument();
        $document->name = 'prepersist';
    }

    public function postPersist(EventArgs $e)
    {
        $document = $e->getDocument();
        $document->username = 'postpersist';
    }

    public function preUpdate(EventArgs $e)
    {
        $document = $e->getDocument();
        $document->name = 'preupdate';
    }

    public function postUpdate(EventArgs $e)
    {
        $document = $e->getDocument();
        $document->username = 'postupdate';
    }

    public function preMove(EventArgs $e)
    {
        $this->preMove = true;
        $document = $e->getDocument();
        $document->name = 'premove'; // I try to update the name of the document but after move, the document should never be modified
        $document->username = 'premove';
    }

    public function postMove(EventArgs $e)
    {
        $document = $e->getDocument();
        $document->username .= '-postmove';
    }
}
