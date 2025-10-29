<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Rule;
use Livewire\Component;

class ContactForm extends Component
{
    #[Rule('required|string|min:2')]
    public string $name = '';

    #[Rule('required|email')]
    public string $email = '';

    #[Rule('required|string|min:10')]
    public string $message = '';

    public bool $sent = false;

    public function submit(): void
    {
        $this->validate();

        // Send email to the site's from address; reply-to is the sender
        try {
            $recipient = env('CONTACT_RECIPIENT', (string) config('mail.from.address'));
            Mail::raw($this->message, function ($mail) use ($recipient) {
                $mail->to($recipient)
                     ->replyTo($this->email, $this->name)
                     ->subject('Contact form message from ' . $this->name);
            });
        } catch (\Throwable $e) {
            // Keep UX smooth even if mail fails; in real app you might log this
        }

        $this->reset(['name', 'email', 'message']);
        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}


