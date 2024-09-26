<?php

namespace App\Mail;

use App\Models\Tarefa;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class NovatarefaMail extends Mailable
{
    use Queueable, SerializesModels;
    public $tarefa;
    public $data_limite_conclusao;
    public $url;
    /**
     * Create a new message instance.
     */
    public function __construct(Tarefa $tarefa)
    {
        $this->tarefa = $tarefa->tarefa;
        $this->data_limite_conclusao = date('d/m/Y', strtotime($tarefa->data_limite_conclusao));
        $this->url = 'http://localhost:8000/tarefa/'.$tarefa->id;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Novatarefa Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.nova-tarefa',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        return $this->markdown('emails.nova-tarefa')->subject('Nova tarefa criada');
    }
}
