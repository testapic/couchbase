<?php
namespace Couchbase\Service;

use Zend\Config\Config;
use Zend\Json\Json;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Service class providing access to couchbase server
 */
class Couchbase
{
    /**
     * Construct CouchbaseClient service.
     * Will throw a RuntimeException, if php couchbase extension
     * is not installed. Install using:
     *
     *   shell> sudo pecl install couchbase
     *
     * @param Config $config
     * @throws \RuntimeException
     */
    public function __construct(Config $config)
    {
        if (!extension_loaded('couchbase')) {
            throw new \RuntimeException('Couchbase extension not loaded. Skipping');
        }
        $this->setConfig($config);
    }

    /**
     * Connect to couchbase server
     */
    protected function connect()
    {
        // prepare CouchbaseCLient
        $config = $this->getConfig();
        $client = new \CouchbaseCluster($config->get('server'));

		$toTest = $config->get('password');
		if(isset($toTest)) {
			$this->setCouchbaseClient($client->openBucket($config->get('bucket'), $config->get('password')));
		} else {
			$this->setCouchbaseClient($client->openBucket($config->get('bucket')));
		}

    }

    /**
     * Push data into bucket
     * @param string $key
     * @param array|object|string $data
     */
    public function set($key, $data)
    {
        // extract object to array
        if (is_object($data)) {
            $data = $this->getHydrator()->extract($data);
        }

        // JSON encode is array
        if (is_array($data)) {
            $data = Json::encode($data);
        }

        $this->getCouchbaseClient()->upsert($key, $data);
    }

    /**
     * Get Entity from Bucket by given key
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
		try {
        	$data = $this->getCouchbaseClient()->get($key);

        	// try to json decode data back to array
        	$data = Json::decode($data->value, Json::TYPE_ARRAY);
        }
        catch(\CouchbaseException $e){ // The key don't exist
        	$data=null;
        }
        catch (\Exception $e) { /* Do Nothing */ }

        return $data;
    }


    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config
     * @return Couchbase
     */
    public function setConfig($config)
    {
    	$this->config = $config;
    	return $this;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
    	return $this->config;
    }

    /**
     * @var CouchbaseClient
     */
    private $couchbaseClient;

    /**
     * @param CouchbaseClient
     * @return Couchbase
     */
    public function setCouchbaseClient($client)
    {
    	$this->couchbaseClient = $client;
    	return $this;
    }

    /**
     * @return CouchbaseClient
     */
    public function getCouchbaseClient()
    {
        // connect
        if (!$this->couchbaseClient) {
            $this->connect();
        }
    	return $this->couchbaseClient;
    }


    /**
     * @var ClassMethods
     */
    private $hydrator;

    /**
     * @param ClassMethods
     * @return Couchbase
     */
    public function setHydrator($hydrator)
    {
    	$this->hydrator = $hydrator;
    	return $this;
    }

    /**
     * @return ClassMethods
     */
    public function getHydrator()
    {
        if (!$this->hydrator) {
            return new ClassMethods();
        }
    	return $this->hydrator;
    }
}
