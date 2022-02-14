<?php

namespace App\Subscriber;

use App\Entity\Connection;
use App\Repository\SensorRepository;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BeforeConnectionPersistedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected SensorRepository $sensorRepository
    )
    {
    }

    /**
     * @return array<string, array> The event names to listen to
     */
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => [
                ['process', 10],
            ],
            BeforeEntityUpdatedEvent::class => [
                ['process', 10],
            ],
        ];
    }

    public function process(BeforeEntityPersistedEvent | BeforeEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();
        if (!($entity instanceof Connection)) {
            return;
        }
        $sensorA = $this->sensorRepository->getById($entity->getSensorAId());
        $sensorB = $this->sensorRepository->getById($entity->getSensorBId());

        $event->getEntityInstance()->setSensorA($sensorA);
        $event->getEntityInstance()->setSensorB($sensorB);
    }
}
