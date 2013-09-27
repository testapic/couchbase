<?php
namespace Couchbase;

/**
 * Couchbase connectivity module
 */
class Module
{
    /**
     * Autoload config
     * array
     */
	public function getAutoloaderConfig()
	{
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
				)
			)
		);
	}

	/**
	 * Service configuration
	 * @return array
	 */
	public function getServiceConfig()
	{
		return array(
			'factories' => array(
				'service_couchbase'   => 'Couchbase\Service\CouchbaseFactory',
			),
		);
	}

	/**
	 * Get module config
	 * @return array
	 */
	public function getConfig()
	{
	    return include __DIR__ . '/config/couchbase.config.php';
	}
}
