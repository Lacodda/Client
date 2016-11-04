<?php

    namespace App\Mail;

    use Carbon\Carbon;
    use Illuminate\Bus\Queueable;
    use Illuminate\Mail\Mailable;
    use Illuminate\Queue\SerializesModels;

    class Update
        extends Mailable
    {
        use Queueable, SerializesModels;

        protected $update;

        /**
         * Create a new message instance.
         *
         * @return void
         */
        public function __construct ($update)
        {
            $this->update = $update;
        }

        /**
         * Build the message.
         *
         * @return $this
         */
        public function build ()
        {
            return $this->from (getenv ('MAIL_USERNAME'))->view ('emails.update')->subject ('Отчет от ' . Carbon::now ('Europe/Samara'))->with (
                [
                    'clients'   => $this->update['clients'],
                    'documents' => $this->update['documents'],
                ]
            );
        }
    }
