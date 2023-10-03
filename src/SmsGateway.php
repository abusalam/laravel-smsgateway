<?php

namespace AbuSalam;

use AbuSalam\Contracts\SmsGatewayContract;

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

    protected $templateid;

    protected $securekey;

    protected $tag;

    protected $payload;

    protected $response;

    public function __construct()
    {
        return $this->setGateway()
            ->setApiEndpoint()
            ->setUsername()
            ->setSenderid()
            ->setSecurekey()
            ->setPayload($this->buildConfigParams());
    }

    public function sendSms()
    {
        $this->hashPayload();
        $post = curl_init();
        //curl_setopt($post, CURLOPT_SSLVERSION, 5); // uncomment for systems supporting TLSv1.1 only
        //curl_setopt($post, CURLOPT_SSLVERSION, 6); // use for systems supporting TLSv1.2 or comment the line
        curl_setopt($post, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($post, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($post, CURLOPT_URL, $this->getApiEndpoint());
        curl_setopt($post, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->getSecurekey()));
        curl_setopt($post, CURLOPT_POSTFIELDS, http_build_query($this->getPayload()));
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
        $resp['data'] = curl_exec($post);
        $resp['payload'] = $this->getPayload();
        if($resp['data'] === false) {
            $resp['error'] =  curl_error($post);
        }
        curl_close($post);
        $this->setResponse($resp);
        return $this->getResponse();
    }

    protected function hashPayload()
    {
        $payload = $this->getPayload();
        $form_params = [
            config('smsgateway.' . config('smsgateway.default') . '.apiMobileNoParam') => $this->getRecipients(),
            config('smsgateway.' . config('smsgateway.default') . '.apiSmsParam') => $this->getContents(),
            config('smsgateway.' . config('smsgateway.default') . '.apiTemplateIdParam') => $this->getTemplateId(),
            config('smsgateway.' . config('smsgateway.default') . '.apiTagParam') => $this->getTag(),
        ];
        $payload = array_merge($form_params, $payload);

        if (array_key_exists('key', $payload)) {
            $payload['key'] = hash('sha512', $this->getUsername().$this->getSenderid().$this->getContents().$this->getSecurekey());
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
            "smsservicetype" =>"singlemsg",
        ];
        $payload = array_merge($form_params, $configParams);

        return $payload;
    }

    /**
     * Gets the Message for the SMS
     * @param  string
     * @return self
     */
    public function withSms($contents = '')
    {
        $this->setContents($contents);
        $this->hashPayload();
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemplateId()
    {
        return $this->templateid;
    }

    /**
     * @param mixed $templateId
     *
     * @return self
     */
    public function withTemplateId($templateId = '')
    {
        $this->templateid = $templateId == ''
        ? config('smsgateway.' . $this->getGateway() . '.apiValues.apiTemplateId')
        : trim($templateId);
        $payload = $this->getPayload();
        if(is_array($payload)) {
            if (array_key_exists('templateid', $payload)) {
                $payload['templateid'] = $this->templateid;
                $this->setPayload($payload);
            }
        }
        return $this;
    }

    /**
     * Gets the Tag for the SMS
     * @param  mixed
     * @return self
     */
    public function withTag($tag)
    {
        $this->setTag($tag);
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
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param mixed $tag
     *
     * @return self
     */
    protected function setTag($tag)
    {
        $this->tag = $tag;

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
        //dump($payload);
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
        if (array_key_exists('smsservicetype', $payload)) {
            $payload['smsservicetype'] = 'otpmsg';
            $this->setPayload($payload);
        }
        return $this;
    }

    public function asUnicodeOtpSms()
    {
        $payload = $this->getPayload();
        if (array_key_exists('smsservicetype', $payload)) {
            $payload['smsservicetype'] = 'unicodeotpmsg';
            $this->setPayload($payload);
        }
        return $this;
    }

    public function asUnicodeSms()
    {
        $payload = $this->getPayload();
        if (array_key_exists('smsservicetype', $payload)) {
            $payload['smsservicetype'] = 'unicodemsg';
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
        if (array_key_exists('smsservicetype', $payload)) {
            $payload['smsservicetype'] = 'bulkmsg';
            $this->setPayload($payload);
        }
        return $this;
    }
}
