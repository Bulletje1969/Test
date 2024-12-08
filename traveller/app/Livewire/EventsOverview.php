<?php

namespace App\Livewire;

use DateTime;
use DateTimeZone;
use Livewire\Component;

class EventsOverview extends Component
{
    public $flightNumber = '';
    public $flights;
    public $selectedFlight;
    public $flightExists;
    public $events;
    public $selectedEvent;
    public $guestName;
    public $guestEmail;
    public $numberOfPeople;
    public $transTime;
    public $travelTime;

    public function mount()
    {
        $this->getFlights();
    }

    public function filterEvents()
    {
        $this->checkFlight();
        if ($this->flightExists) {
            $this->getEvents();
            $this->transTime = $transitTime = $this->getTransitTime($this->flightNumber);
            $this->events = collect($this->events)->filter(function ($event) use ($transitTime) {
                return $this->calculateTotalTime($event) <= $transitTime;
            })->toArray();
        }
    }

    private function calculateTotalTime($event)
    {
        $this->travelTime = ($event['distance'] / 20) * 2; // Convert distance to time (20 km/h)
        return $event['duration'] + $this->travelTime + 2.5; // Add the returntime
    }

    private function checkFlight()
    {
        $this->events = [];
        $this->flightExists = collect($this->flights)->contains('flightNumber', $this->flightNumber);
    }

    public function selectEvent($eventId)
    {
        $this->selectedEvent = collect($this->events)->firstWhere('id', $eventId);
    }

    private function getTransitTime(string $flightNumber): float
    {
        $this->selectedFlight = collect($this->flights)->firstWhere('flightNumber', $flightNumber);
        if ($this->selectedFlight) {
            $departure = $this->selectedFlight['departureTime'];
            $now = (new DateTime())->format('Y-m-d H:i:s');
            return $this->getHourDifference($now, $departure);
        }
        return 0;
    }

    private function getHourDifference(string $date1, string $date2): float
    {
        $datetime1 = new DateTime($date1);
        $datetime2 = new DateTime($date2);
        $interval = $datetime1->diff($datetime2);

        return round($interval->days * 24 + $interval->h + $interval->i / 60);
    }
    
    private function getEvents()
    {
        $this->events = [
            ['id' => 1, 'name' => 'Museum Visit', 'duration' => 2, 'price' => 15, 'distance' => 3],
            ['id' => 2, 'name' => 'Theatre Visit', 'duration' => 3.5, 'price' => 25, 'distance' => 3.5],
            ['id' => 3, 'name' => 'Cinema Visit', 'duration' => 2.5, 'price' => 12, 'distance' => 2.5],
            ['id' => 4, 'name' => 'Canal Cruise', 'duration' => 1.5, 'price' => 20, 'distance' => 2],
        ];
    }

    private function getFlights()
    {
        $this->flights = [];
        $date = new DateTime('', new DateTimeZone('CET'));
        $j = 6;

        for ($i = 10; $i < 21; $i++) {
            $currentDate = (clone $date)->modify("+$j hours");
            $this->flights[] = [
                'flightNumber' => '10' . $i,
                'date' => $currentDate->format('Y-m-d'),
                'time' => $currentDate->format('H:i'),
                'departureTime' => $currentDate->format('Y-m-d H:i:s T')
            ];
            $j++;
        }
    }

    public function render()
    {
        return view('livewire.events-overview');
    }
}
