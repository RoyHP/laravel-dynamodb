<?php

namespace BaoPham\DynamoDb\Tests;

use Aws\DynamoDb\Marshaler;
use BaoPham\DynamoDb\DynamoDbClientService;
use BaoPham\DynamoDb\DynamoDbModel;
use BaoPham\DynamoDb\EmptyAttributeFilter;

/**
 * Class ModelTest
 *
 * @package BaoPham\DynamoDb\Tests
 */
abstract class ModelTest extends TestCase
{
    /**
     * @var \Aws\DynamoDb\DynamoDbClient
     */
    protected $dynamoDbClient;

    /**
     * @var \BaoPham\DynamoDb\DynamoDbClientService
     */
    protected $dynamoDb;

    /**
     * @var TestModel
     */
    protected $testModel;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->bindDynamoDbClientInstance();
    }

    protected function bindDynamoDbClientInstance()
    {
        $marshalerOptions = [
            'nullify_invalid' => true,
        ];

        $config = [
            'credentials' => [
                'key' => 'dynamodb_local',
                'secret' => 'secret',
            ],
            'region' => 'test',
            'version' => '2012-08-10',
            'endpoint' => 'http://localhost:3000',
        ];

        $this->dynamoDb = new DynamoDbClientService($config, new Marshaler($marshalerOptions),
            new EmptyAttributeFilter);

        // Set the DynamoDbClient, this is handled by the DynamoDbServiceProvider boot in normal use.
        DynamoDbModel::setDynamoDbClientService($this->dynamoDb);

        $this->testModel = $this->getTestModel();

        $this->dynamoDbClient = $this->dynamoDb->getClient();
    }

    abstract protected function getTestModel();

    protected function setUpDatabase()
    {
        copy(dirname(__FILE__) . '/../dynamodb_local_init.db', dirname(__FILE__) . '/../dynamodb_local_test.db');
    }
}
