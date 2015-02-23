Couchbase connectivity
======================

This Zend Framework 2 module provides connectivity for Couchbase clusters.

## Installation

Install the module by adding the repository to your composer.json file:

	{
		"repositories": [
	        {
	            "type": "vcs",
	            "url": "https://github.com/netzfabrik/couchbase"
	        }
	    ],
	    "require": {
	        "netzfabrik/couchbase": "*"
	    }
	}	

Afterwards run a composer update to fetch the module to your vendor path

	composer update

Populate the new module in your application.config in the application root and you are done.

	return array(
		'modules' => array(
			'<some_module>',
			'<another_module>',
			'Couchbase'
		),

## Configuration

Place a configuration file in `config\autoload\couchbase.local.php` in your application root config with according settings:

	<?php
	return array(
		'couchbase' => array(
	        'server' => '<couchbase_ip>:<couchbase_port>',
	        'user' => '<username>',
	        'password' => '<password>',
	        'bucket' => '<bucket_name>'
	    )
	);


## Usage

tdb
