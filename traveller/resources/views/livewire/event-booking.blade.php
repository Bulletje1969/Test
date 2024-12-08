<div>
    @if($selectedEvent)
    <h2>Book {{ $selectedEvent['name'] }}</h2>
    @endif

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="bookEvent">
        <div>
            <label>Number of People:</label>
            <input type="number" wire:model="numberOfPeople" min="1">
            @error('numberOfPeople') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Name:</label>
            <input type="text" wire:model="name">
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Email:</label>
            <input type="email" wire:model="email">
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Departure Time:</label>
            <input type="time" wire:model="departureTime" value="">
            @error('departureTime') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Return Time:</label>
            <input type="time" wire:model="returnTime">
            @error('returnTime') <span class="error">{{ $message }}</span> @enderror
        </div>

        <button type="submit">Book Event</button>
    </form>
</div>