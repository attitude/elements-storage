<?php

namespace attitude\Elements;

define('ROOT_DIR', dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));

require_once ROOT_DIR.'/autoload.php';

class UserObjectStorage extends Storage\Blob_Element
{
    public function __construct()
    {
        return parent::__construct();
    }
}

class UserBlobStorage extends StorageFlatfile\Blob_Prototype
{
    public function __construct()
    {
        return parent::__construct();
    }
}

class UserObjectStorage_Test extends \PHPUnit_Framework_TestCase
{
    static $user_object_storage = null;

    /**
     * @expectedException   \attitude\Elements\HTTPException
     */
    public function testConstructionMissingArgument()
    {
        $blobstorage = new UserObjectStorage;
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testConstructionNonstringArgument()
    {
        DependencyContainer::set('attitude\Elements\UserObjectStorage.namespace', 123);
        $blobstorage = new UserObjectStorage();
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testConstructionArgumentWithDiacritics()
    {
        DependencyContainer::set('attitude\Elements\UserObjectStorage.namespace', "kačičky");
        $blobstorage = new UserObjectStorage();
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testConstructionArgumentWithSpace()
    {
        DependencyContainer::set('attitude\Elements\UserObjectStorage.namespace', "word by word");
        $blobstorage = new UserObjectStorage();
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testConstructionEmptyStringArgument()
    {
        DependencyContainer::set('attitude\Elements\UserObjectStorage.namespace', '');
        $blobstorage = new UserObjectStorage();
    }

    public function testSuccessfulConstruction()
    {
        DependencyContainer::set('attitude\Elements\UserObjectStorage.namespace', 'users');
        DependencyContainer::set('attitude\Elements\UserObjectStorage.blob_storage(users)', '\attitude\Elements\UserBlobStorage');
        DependencyContainer::set('attitude\Elements\UserBlobStorage.storage_path', ROOT_DIR.'/tmp');
        DependencyContainer::set('attitude\Elements\UserBlobStorage.data_serializer', '\attitude\Elements\SerializerJSON_Element');

        $blobstorage = new UserObjectStorage();

        static::$user_object_storage =& $blobstorage;

        return true;
    }

    public function testFillWithData()
    {
        static::$user_object_storage->store(array(
            "email" => "jozko.mrkvicka@gmail.com",
            "registrationIDs" => array(
                12345667890,
                9876543210
            ),
            "socialMedia" => array(
                "facebook" => array(
                    "id" => 1234
                ),
                "twitter" => array(
                    "id" => 5678
                )
            ),
            "username" => "jozko_mrkvicka"
        ));
    }
}
