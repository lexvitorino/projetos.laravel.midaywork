<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Balance extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $balances;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $balances)
    {
        $this->user = $user;
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
        //$this->to($this->user->email, $this->user->name);
        $this->to("lex.vitorito@gmail.com", "Alex Sousa");

        return $this->markdown('admin.mail.balance', [
            'balance' => $this->balances
        ]);
    }
}
