<?php
require_once('db_mapper_factory.php');
class Logging {
    function __construct($user_id, $logging_type, $logging_content)
    {
        $this->user_id = $user_id;
        $this->logging_type = $logging_type;
        $this->logging_content = $logging_content;
    }
}

function logEvent($user_id, $logging_type, $logging_content)
{
    $dbMapperFactory = new DBMapperFactory();
    $logging_mapper_instance = $dbMapperFactory->createMapperInstance("Logging");
    $conn = $logging_mapper_instance->readFile();

    $success =  $logging_mapper_instance->insertRowToDB($conn, "Logging", new Logging($user_id, $logging_type, $logging_content));

    return $success;
}
?>
