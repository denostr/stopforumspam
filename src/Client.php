<?php

namespace denostr\stopforumspam;

/**
 * Class Client
 *
 * User for access to stopforumspam.com API
 *
 * @package denostr\stopforumspam
 * @author denostr <4deni.kiev@gmail.com>
 * @link https://github.com/denostr/stopforumspam
 * @link http://stopforumspam.com/usage
 */
class Client
{
    /**
     * @var string|array Check IP address
     */
    protected $ip;

    /**
     * @var string|array Check email
     */
    protected $email;

    /**
     * @var string|array Check username
     */
    protected $username;

    /**
     * @var string|array Check emailhash
     */
    protected $emailhash;

    /**
     * @var string Response format
     */
    protected $format;

    /**
     * @var string Callback function, used for self::FORMAT_JSONP format type
     */
    protected $callback;

    /**
     * @var string API Host
     */
    protected $apiHost = 'api.stopforumspam.org';

    /**
     * @var bool Confidence score
     */
    protected $confidence = false;

    /**
     * @var bool To ignore the email/domain list checks
     */
    protected $nobademail = false;

    /**
     * @var bool To ignore the username list checks
     */
    protected $nobadusername = false;

    /**
     * @var bool To ignore the IP lists,
     * which includes some of the Internets most hostile spam friendly networks
     */
    protected $nobadip = false;

    /**
     * @var bool To ignore all wildcard checks
     */
    protected $nobadall = false;

    /**
     * @var bool Any IP address listed that is known as a Tor exit node will return a frequency of 0
     */
    protected $notorexit = false;

    /**
     * @var bool To return a "found" result in an IP address lookup for a known TOR exit node
     */
    protected $badtorexit = false;

    /**
     * @var bool Switch debug mode
     */
    protected $debug = false;

    const FORMAT_XMLCDATA = 'xmlcdata';
    const FORMAT_XMLDOM = 'xmldom';
    const FORMAT_SERIAL = 'serial';
    const FORMAT_JSON = 'json';
    const FORMAT_JSONP = 'jsonp';


    /**
     * Used for access to properties
     *
     * @param $name
     * @param $arguments
     * @return $this
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        if (property_exists($this, $name)) {
            if (count($arguments) == 1) {
                $arguments = current($arguments);
            }

            if (is_bool($this->$name) && !is_bool($arguments)) {
                if ($this->debug) {
                    throw new \Exception('Wrong property format');
                }

                return $this;
            }

            $this->$name = $arguments;

            return $this;
        }

        if (substr($name, 0, 3) === 'get') {
            $name = strtolower(substr($name, 3));

            if (property_exists($this, $name)) {
                return $this->$name;
            }
        }

        if ($this->debug) {
            throw new \Exception('Something going wrong');
        }
    }

    /**
     * Run API reauest
     *
     * @return mixed
     * @throws \Exception
     */
    public function request()
    {
        $data = $this->prepareData();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiHost . '/api');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $this->checkResult($result);

        return $result;
    }

    /**
     * Preparing data before request
     *
     * @return string
     * @throws \Exception
     */
    private function prepareData()
    {
        $data = [];
        foreach (get_object_vars($this) as $property => $value) {
            if (in_array($property, ['apiHost', 'debug'])) {
                continue;
            }

            if ($property == 'format') {
                $data[$value] = '';
            } elseif (is_bool($value) && $value) {
                $data[$property] = '';
            } elseif (!is_null($value) && !is_bool($value)) {
                if (is_array($value)) {
                    foreach ($value as $key => $val) {
                        $value[$key] = $val;
                    }
                } else {
                    $value = $value;
                }

                $data[$property] = $value;
            }
        }

        if (empty($data) && $this->debug) {
            throw new \Exception('Query have not data');
        }

        $data = http_build_query($data);

        return $data;
    }

    /**
     * Check response data
     *
     * @param $result
     * @throws \Exception
     */
    private function checkResult($result)
    {
        if ($this->debug) {
            if (in_array($this->format, [self::FORMAT_JSON, self::FORMAT_JSONP])) {
                $result = json_decode($result, true);
            } elseif ($this->format == self::FORMAT_SERIAL) {
                $result = unserialize($result);
            }

            if (is_array($result)) {
                if (isset($result['success']) && $result['success'] == 0 && isset($result['error'])) {
                    throw new \Exception($result['error']);
                }
            }
        }
    }
}
