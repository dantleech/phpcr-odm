<?php

namespace Doctrine\Tests\ODM\PHPCR\Functional;

use Doctrine\Tests\ODM\PHPCR\PHPCRFunctionalTestCase;
use Doctrine\Tests\Models\CMS\CmsArticle;
use Doctrine\Tests\EventListener\TestIssueXListener;

class EventIssueXTest extends PHPCRFunctionalTestCase
{
    /**
     * @var TestPersistenceListener
     */
    private $listener;

    /**
     * @var \Doctrine\ODM\PHPCR\DocumentManager
     */
    private $dm;

    public function setUp()
    {
        $this->listener = new TestIssueXListener();
        $this->dm = $this->createDocumentManager();
        $this->node = $this->resetFunctionalNode($this->dm);
        $this->dm->getEventManager()->addEventListener(array(
            'onFlush'
        ), $this->listener);

    }

    public function provideData()
    {
        $article1 = new CmsArticle;
        $article1->id = '/functional/article1';
        $article1->date = new \DateTime('2013-04-16');

        $article2 = new CmsArticle;
        $article2->id = '/functional/article2';
        $article2->date = new \DateTime('2013-04-16');

        return array(
            //array(array($article1)),
            array(array($article1, $article2)),
        );
    }

    /**
     * @dataProvider provideData
     */
    public function testTriggerEvents($articles)
    {
        foreach ($articles as $article) {
            $this->dm->persist($article);
            $this->dm->flush();
        }

        // document is OK here, as it has many references. is the same in-memory object
        $preClear = $this->dm->find(null, '/functional/tags/2013-04-16');
        $this->assertCount(count($articles), $preClear->references);

        // remove the document from memory
        $this->dm->clear();

        // reload from DB and it only has 1 of the many articles it should have
        $res = $this->dm->find(null, '/functional/tags/2013-04-16');
        $this->assertNotNull($res);
        $this->assertCount(count($articles), $res->references);
    }
}
