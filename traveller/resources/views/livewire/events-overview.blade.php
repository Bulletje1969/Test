<div>
    <h1>Amsterdam Transit Events</h1>
    
    <input wire:model="flightNumber" placeholder="1010..1020">
    <button wire:click="filterEvents">Filter Events</button>

    @if($flightExists)
        <div>
            <h2>Flight number {{ $selectedFlight['flightNumber'] }}</h2>
            <p>Transit Time {{ $transTime }} hours</p>
            <p>Departure time: {{ $selectedFlight['time'] }}</p>
        </div>
    @elseif($flightNumber !== '')
        <div>
            <h2>Flight number {{ $flightNumber }} not found!!</h2>
        </div>
    @endif

    @if($events)
        @foreach($events as $event)
            <div>
                <h2>{{ $event['name'] }}</h2>
                <p>Distance: {{ $event['distance'] }} km</p>
                <p>Travel time: {{ $travelTime }} hours</p>
                <p>Duration: {{ $event['duration'] }} hours</p>
                <p>Price: €{{ $event['price'] }}</p>
                <button wire:click="selectEvent({{ $event['id'] }})">Book Now</button>
            </div>
        @endforeach
    @endif

    @if($selectedEvent)
        <livewire:event-booking :selectedEvent="$selectedEvent" :selectedFlight="$selectedFlight"/>
    @endif

</div>