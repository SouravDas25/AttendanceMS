<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterUser extends Mailable
{
    use Queueable, SerializesModels;


    public $name;
    public $email;
    public $password;
    public $admin_email;
    public $admin_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name,$email,$token,$admin_email,$admin_name)
    {
        $this->name = $name;
        $this->email = $email;
        $this->token = $token;
        $this->admin_email = $admin_email;
        $this->admin_name = $admin_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('tict.edu.attendence@gmail.com')
        ->view('emails.user_created')
        ->replyTo($this->admin_email, $this->admin_name)
        ->subject("Registration Successfull")
        ->with([
            'name' => $this->name,
            'email' => $this->email,
            'token' => $this->token,
            'admin_email' => $this->admin_email,
            'admin_name'=>$this->admin_name,
        ]);
    }
}
