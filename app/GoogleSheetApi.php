<?php
    namespace App;

    use Google\Spreadsheet\DefaultServiceRequest;
    use Google\Spreadsheet\ServiceRequestFactory;

    class GoogleSheetApi
    {
        private $client;

        private $accessToken;

        private $worksheetFeed;

        private $cellFeed;

        private $fileId = '';

        private $documentsTab = 'DOCUMENTS';

        private $clientsTab = 'CLIENTS';

        /**
         * @param string $fileId
         */
        public function setFileId ($fileId)
        {
            $this->fileId = $fileId;
        }

        /**
         * @param string $documentsTab
         */
        public function setDocumentsTab ($documentsTab)
        {
            $this->documentsTab = $documentsTab;
        }

        /**
         * @param string $clientsTab
         */
        public function setClientsTab ($clientsTab)
        {
            $this->clientsTab = $clientsTab;
        }

        /**
         * Google_Spreadsheet constructor.
         */
        public function __construct ()
        {
            self::access ();
        }

        private function isWebRequest ()
        {
            return isset($_SERVER['HTTP_USER_AGENT']);
        }

        private function pageHeader ($title)
        {
            $ret = "<!doctype html>
  <html>
  <head>
    <title>" . $title . "</title>
    <link href='styles/style.css' rel='stylesheet' type='text/css' />
  </head>
  <body>\n";
            if ($_SERVER['PHP_SELF'] != "/index.php")
            {
                $ret .= "<p><a href='index.php'>Back</a></p>";
            }
            $ret .= "<header><h1>" . $title . "</h1></header>";

            // Start the session (for storing access tokens and things)
            if (!headers_sent ())
            {
                session_start ();
            }

            return $ret;
        }

        private function pageFooter ($file = null)
        {
            $ret = "";
            if ($file)
            {
                $ret .= "<h3>Code:</h3>";
                $ret .= "<pre class='code'>";
                $ret .= htmlspecialchars (file_get_contents ($file));
                $ret .= "</pre>";
            }
            $ret .= "</html>";

            return $ret;
        }

        private function missingApiKeyWarning ()
        {
            $ret = "
    <h3 class='warn'>
      Warning: You need to set a Simple API Access key from the
      <a href='http://developers.google.com/console'>Google API console</a>
    </h3>";

            return $ret;
        }

        private function missingClientSecretsWarning ()
        {
            $ret = "
    <h3 class='warn'>
      Warning: You need to set Client ID, Client Secret and Redirect URI from the
      <a href='http://developers.google.com/console'>Google API console</a>
    </h3>";

            return $ret;
        }

        private function missingServiceAccountDetailsWarning ()
        {
            $ret = "
    <h3 class='warn'>
      Warning: You need download your Service Account Credentials JSON from the
      <a href='http://developers.google.com/console'>Google API console</a>.
    </h3>
    <p>
      Once downloaded, move them into the root directory of this repository and
      rename them 'service-account-credentials.json'.
    </p>
    <p>
      In your application, you should set the GOOGLE_APPLICATION_CREDENTIALS environment variable
      as the path to this file, but in the context of this example we will do this for you.
    </p>";

            return $ret;
        }

        private function missingOAuth2CredentialsWarning ()
        {
            $ret = "
    <h3 class='warn'>
      Warning: You need to set the location of your OAuth2 Client Credentials from the
      <a href='http://developers.google.com/console'>Google API console</a>.
    </h3>
    <p>
      Once downloaded, move them into the root directory of this repository and
      rename them 'oauth-credentials.json'.
    </p>";

            return $ret;
        }

        private function checkServiceAccountCredentialsFile ()
        {
            // service account creds
            $application_creds = __DIR__ . '/../../service-account-credentials.json';

            return file_exists ($application_creds) ? $application_creds : false;
        }

        private function getOAuthCredentialsFile ()
        {
            // oauth2 creds
            $oauth_creds = __DIR__ . '/../../oauth-credentials.json';

            if (file_exists ($oauth_creds))
            {
                return $oauth_creds;
            }

            return false;
        }

        private function setClientCredentialsFile ($apiKey)
        {
            $file = __DIR__ . '/../../tests/.apiKey';
            file_put_contents ($file, $apiKey);
        }

        function getApiKey ()
        {
            $file = __DIR__ . '/../../tests/.apiKey';
            if (file_exists ($file))
            {
                return file_get_contents ($file);
            }
        }

        private function setApiKey ($apiKey)
        {
            $file = __DIR__ . '/../../tests/.apiKey';
            file_put_contents ($file, $apiKey);
        }

        private function access ()
        {
            $this->client = new \Google_Client();

            /************************************************
             * ATTENTION: Fill in these values, or make sure you
             * have set the GOOGLE_APPLICATION_CREDENTIALS
             * environment variable. You can get these credentials
             * by creating a new Service Account in the
             * API console. Be sure to store the key file
             * somewhere you can get to it - though in real
             * operations you'd want to make sure it wasn't
             * accessible from the webserver!
             ************************************************/

            putenv ("GOOGLE_APPLICATION_CREDENTIALS=" . __DIR__ . "/client_secret.json");

            if ($credentials_file = $this->checkServiceAccountCredentialsFile ())
            {
                // set the location manually
                $this->client->setAuthConfig ($credentials_file);
            } elseif (getenv ('GOOGLE_APPLICATION_CREDENTIALS'))
            {
                // use the application default credentials
                $this->client->useApplicationDefaultCredentials ();
            } else
            {
                echo $this->missingServiceAccountDetailsWarning ();
                exit;
            }

            $this->client->setApplicationName ("Google Sheets API");

            $this->client->setScopes (['https://www.googleapis.com/auth/drive', 'https://spreadsheets.google.com/feeds']);

            $tokenArray = $this->client->fetchAccessTokenWithAssertion ();

            $this->accessToken = $tokenArray["access_token"];
        }

        public function getFile ()
        {
            $service = new \Google_Service_Drive($this->client);

            $results = $service->files->get ($this->fileId);

            $serviceRequest = new DefaultServiceRequest($this->accessToken);

            ServiceRequestFactory::setInstance ($serviceRequest);

            $spreadsheetService = new \Google\Spreadsheet\SpreadsheetService();

            $spreadsheetFeed = $spreadsheetService->getSpreadsheetFeed ();

            $spreadsheet = $spreadsheetFeed->getByTitle ($results->name);

            $this->worksheetFeed = $spreadsheet->getWorksheetFeed ();
        }

        public function getTab ($tabName)
        {
            $worksheet = $this->worksheetFeed->getByTitle ($tabName);

            $cellFeed = $worksheet->getCellFeed ();

            $this->cellFeed = $cellFeed->toArray ();
        }

        public function getClient ($clientId)
        {
            $this->getFile ();
            $this->getTab ($this->clientsTab);
            $result = [];
            foreach ($this->cellFeed as $row)
            {
                if ($row[1] == $clientId)
                {
                    $result[] = $row;
                }
            }

            return $result;
        }

        public function getClients ()
        {
            $this->getFile ();
            $this->getTab ($this->clientsTab);
            // Удаляем первую строку с заголовком таблицы
            array_shift ($this->cellFeed);

            return $this->cellFeed;
        }

        public function getDocuments ()
        {
            $this->getFile ();
            $this->getTab ($this->documentsTab);

            return $this->cellFeed;
        }

        public function getDocumentsByClientId ($clientId, $finished = true)
        {
            $this->getFile ();
            $this->getTab ($this->documentsTab);
            $result = [];
            foreach ($this->cellFeed as $row)
            {
                if ($row[11] == $clientId && $row[1] == 'F')
                {
                    $result[] = $row;
                }
            }

            return $result;
        }
    }