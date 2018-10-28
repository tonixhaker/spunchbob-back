<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sichikawa\LaravelSendgridDriver\SendGrid;


class NotRegisteredUserOrder extends Mailable
{
    use Queueable, SerializesModels,SendGrid;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $user;

    /**
     * NotRegisteredUserOrder constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }



    public function build()
    {

        return $this->view('emails.test')
            ->subject('subject')
            ->from('from@example.com')
            ->to(['to@example.com'])
            ->sendgrid([
                'personalizations' => [
                    [
                        'substitutions' => [
                            ':myname' => 's-ichikawa',
                        ],
                    ],
                ],
            ]);
    }
}
