<?php

use Livewire\Component;

new class extends Component
{
    public $count = 0;

    public function increment()
    {
        $this->count++;
    }
}; ?>

<div>
    <button
        type="button"
        wire:click="increment"
        class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
        wire:loading.attr="disabled"
    >
        <span wire:loading.remove>Increment</span>
        <span wire:loading wire:target="increment">
            <svg class="animate-spin h-5 w-5 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Incrementing...
        </span>
    </button>

    <div class="mt-6 text-2xl font-bold text-gray-800 dark:text-white">
        Count: {{ $count }}
    </div>
</div>
