<?php
namespace App\Http\Livewire;

use Livewire\Component;

class RefuseTicket extends Component
{
    public $ticketId;
    public $comment;

    protected $rules = [
        'comment' => 'required|string|max:255',
    ];

    public function submit()
    {
        $this->validate();

        // Add your logic to handle the comment and refuse the ticket
        // Example:
        // $ticket = Ticket::find($this->ticketId);
        // $ticket->status = 'refused';
        // $ticket->comment = $this->comment;
        // $ticket->save();

        $this->emit('ticketRefused'); // Emit an event to notify the parent component
        $this->reset('comment');
        $this->dispatchBrowserEvent('close-popup');
    }

    public function render()
    {
        return view('livewire.refuse-ticket');
    }
}