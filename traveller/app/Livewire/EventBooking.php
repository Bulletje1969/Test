<?php

namespace App\Livewire;

use DateTime;
use Livewire\Component;

class EventBooking extends Component
{
    public $selectedEvent;
    public $selectedFlight;
    public $numberOfPeople = 1;
    public $isRegistered = false;
    public $name;
    public $email;
    public $departureTime;
    public $returnTime;

    public function mount()
    {
        $this->departureTime = $this->selectedFlight['time'];
        $this->returnTime = $this->calculateReturnTime($this->selectedEvent, $this->selectedFlight['departureTime']);
    }

    public function bookEvent()
    {
        $this->validate([
            'numberOfPeople' => 'required|integer|min:1',
            'name' => 'required',
            'email' => 'required|email',
            'departureTime' => 'required|date_format:H:i',
            'returnTime' => 'required|date_format:H:i|before:departureTime',
        ]);

        $departureDateTime = \Carbon\Carbon::createFromFormat('H:i', $this->departureTime);
        $returnDateTime = \Carbon\Carbon::createFromFormat('H:i', $this->returnTime);

        // Book the event
        // Book Schulte bus for outward journey
        // Book Schulte bus for return journey

        session()->flash('message', 'Booking successful!');
        $this->reset(['selectedEvent', 'numberOfPeople', 'name', 'email', 'departureTime', 'returnTime']);
    }

    private function calculateReturnTime($event, $departTime)
    {
        $travelTime = ($event['distance'] / 20) * 2;
        $returnTime = ceil($travelTime + 2.5);
        $departDateTime = new DateTime($departTime);
        $departDateTime->modify("-$returnTime hours");
        return $departDateTime->format('H:i');
    }

    public function render()
    {
        return view('livewire.event-booking');
    }
}
