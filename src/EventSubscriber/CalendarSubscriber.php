<?php

namespace App\EventSubscriber;

use App\Repository\CongeRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{

    private $router;
    private $congeRepository;

    public function __construct(  CongeRepository $congeRepository,  UrlGeneratorInterface $router)
    {
        $this->congeRepository = $congeRepository;
        $this->router = $router;
    }
    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        // You may want to make a custom query from your database to fill the calendar

        // Modify the query to fit to your entity and needs
        // Change b.beginAt by your start date in your custom entity
        $conges = $this->congeRepository
            ->createQueryBuilder('b')
            ->andWhere('b.dateDebut BETWEEN :startDate and :endDate')
            ->andWhere("b.etat='Validé'  ")
            ->setParameter('startDate', $start->format('Y-m-d H:i:s'))
            ->setParameter('endDate', $end->format('Y-m-d H:i:s'))
            ->getQuery()->getResult();

        foreach($conges as $conge) {
            // this create the events with your own entity (here booking entity) to populate calendar
            $CongeEvent = new Event(
                $conge->getMotif(),
               // $conge->getIdEmployee(),
                $conge->getDateDebut(),
                $conge->getDateFin() // If the end date is null or not defined, it creates a all day event
            );
            /*
            * Add custom options to events
            *
            * For more information see: https://fullcalendar.io/docs/event-object
            * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
            */

            $CongeEvent->setOptions([
                'backgroundColor' => 'red',
                'borderColor' => 'red',
            ]);
            $CongeEvent->addOption(
                'url',
                $this->router->generate('listConge', [
                    'id' => $conge->getId(),
                ])
            );

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($CongeEvent);

        }
    }
}