<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;


class ProcessEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    protected $emails;


    public function __construct($order)
    {
        $this->order = $order;
        $this->emails = $this->order->getEmails();
    }

    private function emails()
    {
        for ($i = 0; $i < count($this->emails); $i++) {
            yield $this->emails[$i];
        }
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->order->calculateTotal();
        foreach ($this->emails() as $email) {
            try {
                Mail::send('emails.completed', ['order' => $this->order], function($message) use ($email)
                {
                    $message->to($email)->subject('Завершенный заказ');
                });
            } catch (\Exception $ex) {
                echo $ex->getMessage();
            }
        }
    }
}
