<?php

    namespace App\Http\Controllers;

    use App\Document;
    use App\Helper;
    use App\Http\Requests;

    class DocumentsController
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

        public function index ()
        {
            //
        }

        public static function getDocumentsByClientId ($client_id, $final = false)
        {
            $filter = ['client_id' => $client_id];

            if ($final)
            {
                $filter = array_add ($filter, 'final', 1);
            }

            $documents = Document::where ($filter)->orderBy ('invoice_date', 'desc')->get ();

            return $documents;
        }

        public static function getDocument ($id, $final = false)
        {
            $document = Document::find ($id);

            if ($final)
            {
                $document = Document::where (['id' => $id, 'final' => 1])->first ();
            }

            if (!$document)
            {
                return false;
            }

            $amount_vat = $document['amount'] - $document['vat'];

            $amount_str = Helper::num2str ($document['amount']);

            return [
                'document'   => $document,
                'amount'     => number_format ($document['amount'], 2, ',', ' '),
                'vat'        => number_format ($document['vat'], 2, ',', ' '),
                'amount_vat' => number_format ($amount_vat, 2, ',', ' '),
                'amount_str' => $amount_str,
            ];
        }
    }
