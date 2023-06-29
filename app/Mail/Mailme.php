<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Mailme extends Mailable {

    use Queueable, SerializesModels;

    private $myAdress;
    private $application;
    private $myFrom;
    public $myMessage='Hello';

    public function __construct($application, $myAdress, $name, $msg, $fromAdress) {
        $this->application = $application;
        $this->myAdress = $myAdress;
        $this->myName = $name;
        $this->myMessage = $msg;
        $this->fromAdress = $fromAdress;
    }

    public function build() {
        $subject = 'sqd.se kontakt';
        //return $this->view('emails.mailme')->from($this->myAdress, $this->myName)->subject($subject);
        
        return $this->view('emails.mailme')->with([
                "application" => $this->application,
                "myMessage" => $this->myMessage,
                "fromAdress" => $this->fromAdress
                ])->from($this->myAdress, $this->myName)->subject($subject);
        
    }

}
