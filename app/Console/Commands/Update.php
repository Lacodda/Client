<?php

    namespace App\Console\Commands;

    use App\Http\Controllers\UpdateController;
    use App\Mail\Update as MailUpdate;
    use Illuminate\Console\Command;
    use Illuminate\Support\Facades\Mail;

    class Update
        extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'client:update';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Update database';

        /**
         * Create a new command instance.
         *
         * @return void
         */
        public function __construct ()
        {
            parent::__construct ();
        }

        /**
         * Execute the console command.
         *
         * @return mixed
         */
        public function handle ()
        {
            if (getenv ('UPDATE') == 'true')
            {
                $updateController = new UpdateController;

                $update = $updateController->update_cli ();

                $clients_created = isset($update['clients']['created']) && $update['clients']['created'] ? count ($update['clients']['created']) : 0;
                $clients_updated = isset($update['clients']['updated']) && $update['clients']['updated'] ? count ($update['clients']['updated']) : 0;
                $clients_deleted = isset($update['clients']['deleted']) && $update['clients']['deleted'] ? count ($update['clients']['deleted']) : 0;
                $documents_created =
                    isset($update['documents']['created']) && $update['documents']['created'] ? count ($update['documents']['created']) : 0;
                $documents_updated =
                    isset($update['documents']['updated']) && $update['documents']['updated'] ? count ($update['documents']['updated']) : 0;
                $documents_deleted =
                    isset($update['documents']['deleted']) && $update['documents']['deleted'] ? count ($update['documents']['deleted']) : 0;

                $this->info (
                    sprintf (
                        'Clients [created: %d; updated: %d; deleted: %d] | Documents [created: %d; updated: %d; deleted: %d]',
                        $clients_created,
                        $clients_updated,
                        $clients_deleted,
                        $documents_created,
                        $documents_updated,
                        $documents_deleted
                    )
                );

                if (getenv ('SEND_MAIL') == 'true')
                {
                    Mail::to (getenv ('MAIL_USERNAME'))->send (new MailUpdate($update));
                }
            }
        }
    }
