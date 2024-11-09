<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DynamicMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $email = '';
    public $body = '';
    public $title = '';
    public $to_user = '';
    public $cc_mail = '';

    public function __construct($email,$body,$title=null,$cc_mail=null)
    {
        $this->email = $email;
        $this->body = $body;
        $this->title = $title;
        $this->cc_mail = $cc_mail;
    }

    public function build()
    {
        $mail = $this->view('admin.mail.dynamic_mail')
                     ->subject($this->title)
                     ->with(['body' => $this->body]);

        if($this->cc_mail!='' && $this->cc_mail!=null){

            $cc_mail_arr = explode(',', $this->cc_mail);

            foreach ($cc_mail_arr as $cc_mail) {
                
                $mail->cc($cc_mail);
            }
        }

        return $mail;
    }
}