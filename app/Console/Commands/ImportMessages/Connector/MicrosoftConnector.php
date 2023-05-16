<?php

namespace App\Console\Commands\ImportMessages\Connector;

use App\Helpers\EmailAttachementNormalized;
use App\Helpers\EmailNormalized;
use App\Helpers\TmpFile;
use Cnsi\Logger\Logger;
use DateTime;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class MicrosoftConnector
{

    protected array $credentials;

    protected Logger $logger;

    private string $accessToken;

    /**
     * @param array $credentials
     * @param Logger $logger
     * @throws IdentityProviderException
     */
    public function __construct(array $credentials, Logger $logger)
    {
        $this->credentials = $credentials;
        $this->logger = $logger;
        $this->microsoftConnection();
    }

    public static function getConnectionLink(): string
    {
        $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId' => config('azure.appId'),
            'clientSecret' => config('azure.appSecret'),
            'redirectUri' => config('azure.redirectUri'),
            'urlAuthorize' => config('azure.authority') . config('azure.authorizeEndpoint'),
            'urlAccessToken' => config('azure.authority') . config('azure.tokenEndpoint'),
            'urlResourceOwnerDetails' => '',
            'scopes' => config('azure.scopes')
        ]);

        $authUrl = $oauthClient->getAuthorizationUrl();
        setting(['tmp.oauthState' => $oauthClient->getState()]);
        setting()->save();

        return $authUrl;
    }

    /**
     * @throws IdentityProviderException
     */
    protected function microsoftConnection(): void
    {
        $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId' => $this->credentials['username'],
            'clientSecret' => $this->credentials['password'],
            'redirectUri' => $this->credentials['host'],
            'urlAuthorize' => config('azure.authority') . config('azure.authorizeEndpoint'),
            'urlAccessToken' => config('azure.authority') . config('azure.tokenEndpoint'),
            'urlResourceOwnerDetails' => '',
            'scopes' => config('azure.scopes')
        ]);

        if (setting('TKGAccessToken') !== null || setting('TKGTokenExpiredTime') !== null && setting('TKGTokenExpiredTime') < time()) {
            $accessToken = $oauthClient->getAccessToken('refresh_token', [
                'refresh_token' => setting('TKGRefreshToken')
            ]);

            setting(['TKGAccessToken' => $accessToken->getToken()]);
            setting(['TKGRefreshToken' => $accessToken->getRefreshToken()]);
            setting(['TKGTokenExpiredTime' => $accessToken->getExpires()]);
            setting()->save();
        }
        $this->accessToken = setting('TKGAccessToken');
    }

    /**
     * @throws \Exception
     */
    public function getEmails($from_date)
    {
        $messageUrlGraph = 'https://graph.microsoft.com/v1.0/me/messages?top=100';

        $listEmails = [];
        $listAttachment = [];
        $curl = curl_init($messageUrlGraph);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $this->accessToken
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        if ($response === false) {
            die('Erreur : ' . curl_error($curl));
        }

        $email_data = json_decode($response, true);
        if (!array_key_exists('error', $email_data)) {
            foreach ($email_data['value'] as $email) {
                if (str_contains($email['sender']['emailAddress']['address'], 'amazon')){

                    if ($email['hasAttachments']) {
                        $url = "https://graph.microsoft.com/v1.0/me/messages/{$email['id']}/attachments";
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            "Authorization: Bearer {$this->accessToken}",
                            "Content-Type: application/json"
                        ));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $response = curl_exec($ch);
                        curl_close($ch);

                        $attachments = json_decode($response)->value;
                        foreach ($attachments as $attachment) {
                            $tmpFile = new TmpFile((string)$attachment->contentBytes);
                            $listAttachment[] = new EmailAttachementNormalized($attachment->name, $tmpFile);
                        }
                    }
                }
                $listEmails[$email['id']] = new EmailNormalized(
                    $email['id'],
                    new DateTime($email['receivedDateTime']),
                    $email['sender']['emailAddress']['address'],
                    $email['body']['content'],
                    $email['from']['emailAddress']['address'],
                    $email['subject'],
                    $email['body']['content'],
                    $listAttachment,
                    $email['hasAttachments'],
                );
            }
        }
        return $listEmails;
    }
}
