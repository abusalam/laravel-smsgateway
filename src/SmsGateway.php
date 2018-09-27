<?php

namespace AbuSalam;

use GuzzleHttp\Client;

/**
 * SMS Gateway Implementation
 */
class SmsGateway implements SmsGatewayContract
{
    protected $recipients;

    protected $contents;

    protected $gateway;

    protected $apiEndpoint;

    protected $username;

    protected $senderid;

    protected $securekey;

    protected $payload;

    protected $response;

    public function __construct()
    {
        return $this->setGateway()
            ->setApiEndpoint()
            ->setUsername()
            ->setSenderid()
            ->setSecurekey();
    }

    public function sendSms()
    {
        $client = new Client();
        $res = $client->request('POST', $this->getApiEndpoint(), $this->getPayload());
        $resp['code']         = $res->getStatusCode();
        $resp['Content-Type'] = $res->getHeaderLine('Content-Type');
        $resp['data']         = $res->getBody()->read(1024);
        $this->setResponse($resp);
        return $this->getResponse();
    }

    protected function hashPayload()
    {
        $payload = $this->buildConfigParams();
        if (array_key_exists('key', $payload['form_params'])) {
            $payload['form_params']['key'] = hash('sha512', $this->getUsername().$this->getSenderid().$this->getContents().$this->getSecurekey());
        }
        //dump("data to hash => ".$this->getUsername().$this->getSenderid().$this->getContents().$this->getSecurekey());
        $this->setPayload($payload);
        return $this;
    }


    protected function buildConfigParams()
    {
        $configParams = array_combine(
            config('smsgateway.' . config('smsgateway.default') . '.apiParams'),
            config('smsgateway.' . config('smsgateway.default') . '.apiValues')
        );

        $form_params = [
            config('smsgateway.' . config('smsgateway.default') . '.apiMobileNoParam') => $this->getRecipients(),
            config('smsgateway.' . config('smsgateway.default') . '.apiSmsParam') => $this->getContents(),
            "smsservicetype" =>"singlemsg",
        ];
        $data = [
            'form_params' => array_merge($form_params, $configParams),
        ];

        //dump($data);
        return $data;
    }

    /**
     * Gets the Message for the SMS
     * @param  string
     * @return [type]
     */
    public function withSms($contents = '')
    {
        $this->setContents($contents);
        $this->hashPayload();
        return $this;
    }

    public function toRecipient($mobile = '')
    {
        return $this->setRecipients($mobile);
    }

    public function fromGateway($gateway = '')
    {
        return $this->setGateway($gateway);
    }

    /**
     * @return mixed
     */
    protected function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * @param mixed $recipients
     *
     * @return self
     */
    protected function setRecipients($recipients)
    {
        $this->recipients = $recipients;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getContents()
    {
        return $this->contents;
    }

    /**
     * @param mixed $contents
     *
     * @return self
     */
    protected function setContents($contents)
    {
        $this->contents = trim($contents);

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param mixed $gateway
     *
     * @return self
     */
    protected function setGateway($gateway = '')
    {
        $this->gateway = $gateway == ''
                ? config('smsgateway.default')
                : trim($gateway);
        return $this;
    }

    /**
     * @return mixed
     */
    protected function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     *
     * @return self
     */
    protected function setUsername($username = '')
    {
        $this->username = $username == ''
            ? config('smsgateway.' . $this->getGateway() . '.apiValues.apiUser')
            : trim($username);
        //dump(config('smsgateway.' . $this->getGateway() . '.apiValues.apiUser'));
        return $this;
    }

    /**
     * @return mixed
     */
    protected function getSenderid()
    {
        return $this->senderid;
    }

    /**
     * @param mixed $senderid
     *
     * @return self
     */
    protected function setSenderid($senderid = '')
    {
        $this->senderid = $senderid == ''
            ? config('smsgateway.' . $this->getGateway() . '.apiValues.apiSenderId')
            : trim($senderid);
        //dump(config('smsgateway.' . $this->getGateway() . '.apiValues.apiSenderId'));
        return $this;
    }

    /**
     * @return mixed
     */
    protected function getSecurekey()
    {
        return $this->securekey;
    }

    /**
     * @param mixed $securekey
     *
     * @return self
     */
    protected function setSecurekey($securekey = '')
    {
        $this->securekey = $securekey == ''
            ? config('smsgateway.' . $this->getGateway() . '.apiValues.apiSecureKey')
            : trim($securekey);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return json_encode($this->response, JSON_PRETTY_PRINT);
    }

    /**
     * @param mixed $response
     *
     * @return self
     */
    protected function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param mixed $payload
     *
     * @return self
     */
    protected function setPayload($payload)
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getApiEndpoint()
    {
        return $this->apiEndpoint;
    }

    /**
     * @param mixed $apiEndpoint
     *
     * @return self
     */
    protected function setApiEndpoint()
    {
        $this->apiEndpoint = config('smsgateway.' . $this->getGateway() . '.apiEndpoint');
        return $this;
    }

    public function asOtpSms()
    {
        $payload = $this->getPayload();
        if (array_key_exists('smsservicetype', $payload['form_params'])) {
            $payload['form_params']['smsservicetype'] = 'otpmsg';
            $this->setPayload($payload);
        }
        return $this;
    }

    public function asUnicodeOtpSms()
    {
        $payload = $this->getPayload();
        if (array_key_exists('smsservicetype', $payload['form_params'])) {
            $payload['form_params']['smsservicetype'] = 'unicodeotpmsg';
            $this->setPayload($payload);
        }
        return $this;
    }

    public function asUnicodeSms()
    {
        $payload = $this->getPayload();
        if (array_key_exists('smsservicetype', $payload['form_params'])) {
            $payload['form_params']['smsservicetype'] = 'unicodemsg';
            $this->setPayload($payload);
        }
        return $this;
    }

    public function asBulkUnicodeSms()
    {
        return $this->asUnicodeSms();
    }
    
    public function asBulkSms()
    {
        $payload = $this->getPayload();
        if (array_key_exists('smsservicetype', $payload['form_params'])) {
            $payload['form_params']['smsservicetype'] = 'bulkmsg';
            $this->setPayload($payload);
        }
        return $this;
    }
}
