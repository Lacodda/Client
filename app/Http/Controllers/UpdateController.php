<?php

    namespace App\Http\Controllers;

    use App\Client;
    use App\Document;
    use App\GoogleSheetApi;
    use App\Helper;
    use App\Http\Requests;
    use Carbon\Carbon;

    class UpdateController
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

        private function clients ()
        {
            $googleSheet = new GoogleSheetApi;

            $documentId = getenv ('GOOGLE_DOCUMENT_ID');

            $googleSheet->setFileId ($documentId);

            $clients = $googleSheet->getClients ();

            $now = Carbon::now ();

            $result = [];

            $clientsIds = [];

            foreach ($clients as $client)
            {
                $saveClient = Client::updateOrCreate (
                    [
                        'alias' => trim ($client[1]),
                    ],
                    [
                        'name'    => trim ($client[2]),
                        'inn'     => trim ($client[3]),
                        'kpp'     => trim ($client[4]),
                        'address' => trim ($client[5]),
                    ]
                );

                $clientsIds[] = $saveClient->id;

                if ($saveClient->created_at == $now)
                {
                    $result['created'][] = $saveClient->name;
                } elseif ($saveClient->updated_at == $now)
                {
                    $result['updated'][] = $saveClient->name;
                }
            }

            // Удалим все записи которых нет в массиве
            $deleted = count (self::clear (new Client(), $clientsIds));
            if ($deleted > 0)
            {
                $result['deleted'] = $deleted;
            }

            return $result;
        }

        private function documents ()
        {
            $googleSheet = new GoogleSheetApi;

            $documentId = getenv ('GOOGLE_DOCUMENT_ID');

            $googleSheet->setFileId ($documentId);

            $documents = $googleSheet->getDocuments ();

            $clients = Client::all ();

            $now = Carbon::now ();

            $result = [];

            $documentsIds = [];

            foreach ($documents as $document)
            {
                $document = [
                    'client_alias'   => isset($document[11]) ? trim ($document[11]) : null,
                    'invoice_number' => isset($document[2]) ? trim ($document[2]) : null,
                    'invoice_date'   => isset($document[3]) ? date ('Y-m-d', strtotime ($document[3])) : null,
                    'act_number'     => isset($document[4]) ? trim ($document[4]) : null,
                    'act_date'       => isset($document[5]) ? date ('Y-m-d', strtotime ($document[5])) : null,
                    'amount'         => isset($document[6]) && $document[6] > 0 ? Helper::strToFloat ($document[6]) : 0,
                    'vat'            => isset($document[7]) && $document[7] > 0 ? Helper::strToFloat ($document[7]) : 0,
                    'final'          => isset($document[1]) && trim ($document[1]) == 'F' ? 1 : 0,
                ];
                if ($document['client_alias'] && $document['invoice_number'] && $document['act_number'])
                {
                    foreach ($clients as $client)
                    {
                        if ($document['client_alias'] == $client->alias)
                        {
                            $saveDocument = Document::updateOrCreate (
                                [
                                    'client_id'      => $client->id,
                                    'invoice_number' => $document['invoice_number'],
                                    'act_number'     => $document['act_number'],
                                ],
                                [
                                    'invoice_date' => $document['invoice_date'],
                                    'act_date'     => $document['act_date'],
                                    'amount'       => $document['amount'],
                                    'vat'          => $document['vat'],
                                    'final'        => $document['final'],
                                ]
                            );

                            $documentsIds[] = $saveDocument->id;

                            if ($saveDocument->created_at == $now)
                            {
                                $result['created'][] = ['invoice_number' => $saveDocument->invoice_number, 'act_number' => $saveDocument->act_number];
                            } elseif ($saveDocument->updated_at == $now)
                            {
                                $result['updated'][] = ['invoice_number' => $saveDocument->invoice_number, 'act_number' => $saveDocument->act_number];
                            }
                        }
                    }
                }
            }

            // Удалим все записи которых нет в массиве
            $deleted = count (self::clear (new Document(), $documentsIds));
            if ($deleted > 0)
            {
                $result['deleted'] = $deleted;
            }

            return $result;
        }

        public function update_cli ()
        {
            $clients = self::clients ();

            $documents = self::documents ();

            return ['clients' => $clients, 'documents' => $documents];
        }

        public function update ()
        {
            $update = self::update_cli ();

            return view ('update', $update);
        }

        private function clear ($class, $ids_array)
        {
            $all = $class::all ();

            $result = [];

            foreach ($all as $item)
            {
                if (!in_array ($item->id, $ids_array))
                {
                    $result[] = $item->id;
                    $item->delete ();
                }
            }

            return $result;
        }
    }
