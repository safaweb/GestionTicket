
@extends('filament::page')

@section('content')
    <div>
        @livewire('refuse-ticket', ['ticketId' => $record->id])
    </div>

    <div x-data="{ open: @entangle('open') }" @open-popup.window="open = true" @close-popup.window="open = false">
    <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-lg">
            <h2 class="text-xl font-bold mb-4">Refuser le Ticket</h2>
            <textarea wire:model="comment" class="w-full border rounded p-2 mb-4" placeholder="Commentaire"></textarea>
            @error('comment') <span class="text-red-500">{{ $message }}</span> @enderror
            <div class="flex justify-end">
                <button @click="open = false" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Annuler</button>
                <button wire:click="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Soumettre</button>
            </div>
        </div>
    </div>
</div>
@endsection