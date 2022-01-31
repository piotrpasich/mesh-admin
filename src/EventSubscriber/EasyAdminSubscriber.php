<?php

namespace App\EventSubscriber;

use App\Entity\BlogPost;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
//    private $slugger;
//
//    public function __construct($slugger)
//    {
//        $this->slugger = $slugger;
//    }

    public static function getSubscribedEvents()
    {
        return [
            AfterCrudActionEvent::class => ['setBlogPostSlug'],
//            BeforeCrudActionEvent::class => ['setBlogPostSlug'],
        ];
    }

    public function setBlogPostSlug( $event)
    {
//        $entity = $event->getEntityInstance();
//        var_dump(get_class($event), $event);
//
//        if (!($entity instanceof BlogPost)) {
//            return;
//        }
//
//        $slug = $this->slugger->slugify($entity->getTitle());
//        $entity->setSlug($slug);
    }
}
