<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Balance extends Mailable
{
    use Queueable, SerializesModels;

    private $toList;
    private $balances;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($toList, $balances)
    {
        $this->toList = $toList;
        $this->balances = $balances;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Banco de Horas');
        $this->to($this->toList);

        return $this->markdown('admin.mail.balance', [
            'balances' => $this->balances
        ]);
    }
}
