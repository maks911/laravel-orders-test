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
        $emailMessage = "Заказ № $this->order->id завершен.<br>";
        $emailMessage .= '<br><br>Состав заказа:<br>';
        foreach ($this->order->products as $key => $product) {
            $emailMessage .= $product->name;
            if ($key !== count($this->order->products) - 1) {
                $emailMessage .= ',';
            }
        }
        $this->order->calculateTotal();
        $emailMessage .= "<br><br>Стоимость: $this->order->total";
        foreach ($this->emails() as $email) {
            Mail::send('emails.completed', array('key' => 'value'), function($message)
            {
                $message->to('foo@example.com', 'Джон Смит')->subject('Привет!');
            });
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            mail($email, 'Заверешенный заказ', $emailMessage, implode("\r\n", $headers));
        }
    }
}
