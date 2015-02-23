<?php
namespace CouchbaseTest\Service;

use Zend\Config\Config;
use Couchbase\Service\CouchbaseFactory as CouchbaseFactory;

class CouchbaseServiceTest extends \PHPUnit_Framework_TestCase
{
	public function testFactory()
	{
		$factory = new CouchbaseFactory();
		$service = $factory->createService($this->getConfiguredServicemanager());
		$this->assertInstanceOf('Couchbase\Service\Couchbase', $service);
	}

	protected function getConfiguredServicemanager()
	{
		$config = array(
			'couchbase' => array(
				'server' => '127.0.0.1:8091',
				'user' => 'test',
				'password' => 'test',
				'bucket' => 'default'
			)
		);

		$serviceManager = $this->getMock('Zend\ServiceManager\ServiceManager', array('get'));
		$serviceManager
			->expects($this->once())
			->method('get')
			->with('Config')
			->will($this->returnValue($config));

		return $serviceManager;
	}
}
