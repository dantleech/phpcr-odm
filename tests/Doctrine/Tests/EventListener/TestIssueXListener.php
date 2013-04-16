<?php

namespace Doctrine\Tests\EventListener;

use Doctrine\ODM\PHPCR\Event\OnFlushEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Cmf\Bundle\RoutingAutoBundle\Document\AutoRoute;
use PHPCR\Util\NodeHelper;
use Doctrine\Tests\Models\CMS\CmsTag;

class TestIssueXListener
{
    public function onFlush(OnFlushEventArgs $args)
    {
        $dm = $args->getDocumentManager();
        $uow = $dm->getUnitOfWork();
        NodeHelper::createPath($dm->getPhpcrSession(), '/functional/tags');
        $parent = $dm->find(null, '/functional/tags');

        $scheduledInserts = $uow->getScheduledInserts();
        $scheduledUpdates = $uow->getScheduledUpdates();
        $updates = array_merge($scheduledInserts, $scheduledUpdates);

        foreach ($updates as $document) {
            $tag = $document->date->format('Y-m-d');
            $tagDoc = $dm->find(null, '/functional/tags/'.$tag);

            if (null === $tagDoc) {
                $tagDoc = new CmsTag;
                $tagDoc->name = $tag;
                $tagDoc->parent = $parent;
            }

            $tagDoc->addDocumentReference($document);
            $dm->persist($tagDoc);
            $uow->computeSingleDocumentChangeSet($tagDoc);
        }
    }
}
