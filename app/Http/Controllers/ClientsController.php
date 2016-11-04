<?php

    namespace App\Http\Controllers;

    use App\Client;
    use App\Http\Requests;

    class ClientsController
        extends Controller
    {
        /**
         * Create a new controller instance.
         *
         * @return void
         */
        public function __construct ()
        {
            //
        }

        public function index ($alias)
        {
            $client = self::getClientByAlias ($alias);
            if (!$client)
            {
                abort (404);
            }

            $documents = DocumentsController::getDocumentsByClientId ($client->id, true);

            return view ('client', ['client' => $client, 'documents' => $documents]);
        }

        public function getClient ($id)
        {
            $client = Client::find ($id);

            return $client;
        }

        public function getClientByAlias ($alias)
        {
            $client = Client::where ('alias', $alias)->first ();

            return $client;
        }

        public function getAct ($alias, $id)
        {
            $client = self::getClientByAlias ($alias);

            if (!$client)
            {
                abort (404);
            }

            $document = DocumentsController::getDocument ($id, true);

            if (!$document)
            {
                abort (404);
            }

            return view ('documents.act', ['client' => $client, 'document' => $document]);
        }

        public function getActStamp ($alias, $id)
        {
            $client = self::getClientByAlias ($alias);

            if (!$client)
            {
                abort (404);
            }

            $document = DocumentsController::getDocument ($id, true);

            if (!$document)
            {
                abort (404);
            }

            return view ('documents.act_stamp', ['client' => $client, 'document' => $document]);
        }

        public function getInvoice ($alias, $id)
        {
            $client = self::getClientByAlias ($alias);

            if (!$client)
            {
                abort (404);
            }

            $document = DocumentsController::getDocument ($id, true);

            if (!$document)
            {
                abort (404);
            }

            return view ('documents.invoice', ['client' => $client, 'document' => $document]);
        }

        public function getInvoiceStamp ($alias, $id)
        {
            $client = self::getClientByAlias ($alias);

            if (!$client)
            {
                abort (404);
            }

            $document = DocumentsController::getDocument ($id, true);

            if (!$document)
            {
                abort (404);
            }

            return view ('documents.invoice_stamp', ['client' => $client, 'document' => $document]);
        }
    }
