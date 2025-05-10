<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewBookingNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The validated booking data array.
     *
     * @var array
     */
    protected $validated = [];

    /**
     * Create a new message instance.
     *
     * @param array $validated
     * @return void
     */
    public function __construct(array $validated)
    {
        $this->validated = $validated;
    }

    /**
     * Set the booking data.
     *
     * @param array $validated
     * @return void
     */
    public function setBooking(array $validated): void
    {
        $this->validated = $validated;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Booking Request Received',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        // Changed from the original 'email' view to match the view path structure
        // you likely have in your application
        return new Content(
            view: 'email', // Use existing view name as it was in original code
            with: $this->prepareData(), // Use helper method to handle potential missing keys
        );
    }

    /**
     * Prepare data for the email template, handling missing keys safely.
     *
     * @return array
     */
    protected function prepareData(): array
    {
        return [
            'first_name' => $this->validated['first_name'] ?? '',
            'last_name' => $this->validated['last_name'] ?? '',
            'email' => $this->validated['email'] ?? '',
            'phone' => $this->validated['phone'] ?? '',
            'order_notes' => $this->validated['order_notes'] ?? '',
            'timetable' => $this->validated['timetable'] ?? '',
            'services' => $this->validated['services'] ?? [],
            'date' => $this->validated['date'] ?? '',
        ];
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [];
    }
}