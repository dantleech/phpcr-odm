<?php

namespace Doctrine\Tests\ODM\PHPCR\Functional;

use Doctrine\Tests\EventListener\TestEventDocumentChangerListener;

class EventComputingTest extends \Doctrine\Tests\ODM\PHPCR\PHPCRFunctionalTestCase
{
    /**
     * @var TestEventDocumentChanger
     */
    private $listener;

    /**
     * @var \Doctrine\ODM\PHPCR\DocumentManager
     */
    private $dm;

    public function setUp()
    {
        $this->listener = new TestEventDocumentChangerListener();
        $this->dm = $this->createDocumentManager();
        $this->node = $this->resetFunctionalNode($this->dm);
        $this->dm->getEventManager()->addEventListener(array('prePersist', 'postPersist', 'preUpdate', 'postUpdate', 'preMove', 'postMove'), $this->listener);
    }

    public function testComputingBetweenEvents()
    {
        // Create initial user
        $user = new \Doctrine\Tests\Models\CMS\CmsUser();
        $user->name = 'mdekrijger';
        $user->username = 'mdekrijger';
        $user->status = 'active';

        // In prepersist the name will be changed
        // In postpersist the username will be changed
        $this->dm->persist($user);
        $this->dm->flush();
        $this->dm->clear();

        // Post persist data is not saved to document, so check before reloading document
        $this->assertTrue($user->username=='postpersist');

        // Be sure that document is really saved by refetching it from ODM
        $user = $this->dm->find('Doctrine\Tests\Models\CMS\CmsUser', $user->id);
        $this->assertEquals('prepersist', $user->name);

        // Change document
        // In preupdate the name will be changed
        // In postupdate the username will be changed
        $user->status = 'changed';
        $this->dm->persist($user);
        $this->dm->flush();
        $this->dm->clear();

        // Post persist data is not saved to document, so check before reloading document
        $this->assertEquals('postupdate', $user->username);

        // Be sure that document is really saved by refetching it from ODM
        $user = $this->dm->find('Doctrine\Tests\Models\CMS\CmsUser', $user->id);
        $this->assertEquals('preupdate', $user->name);

        // Move from /functional/preudpate to /functional/moved
        $targetPath = '/functional/moved';
        $this->dm->move($user, $targetPath);
        $this->dm->flush();
        // we overwrote the name and username fields during the move event, so the object changed
        $this->assertEquals('premove', $user->name);
        $this->assertEquals('premove-postmove', $user->username);

        $this->dm->clear();


        $user = $this->dm->find('Doctrine\Tests\Models\CMS\CmsUser', $targetPath);

        // the document was moved but the only changes applied in preUpdate are persisted,
        // pre/postMove changes are not persisted in that flush
        $this->assertEquals('preupdate', $user->name);
        $this->assertTrue($this->listener->preMove);

        // Clean up
        $this->dm->remove($user);
        $this->dm->flush();
    }
}
