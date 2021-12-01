<?php
//Factory Class Implementation of the Database Mapper classes from ICT2x01 and ICT2106 https://sourcemaking.com/design_patterns/factory_method/php/1
require('fireaway_db_mapper.php');
require('login_mapper.php');
require('account_mapper.php');
require('feedback_mapper.php');
require('token_mapper.php');
require('totp_mapper.php');
require('logging_mapper.php');

class DBMapperFactory
{
    public function createMapperInstance(string $table_name)
    {
        switch ($table_name) {
            case "Login":
                $db_instance = new LoginMapper($table_name);
                break;
            case "Account":
                $db_instance = new AccountMapper($table_name);
                break;
            case "Feedback":
                $db_instance = new FeedbackMapper($table_name);
                break;
            case "TOTP":
                $db_instance = new TOTPMapper($table_name);
                break;
            case "Token":
                $db_instance = new TokenMapper($table_name);
                break;
            case "Logging":
                $db_instance = new LoggingMapper($table_name);
                break;
        }
        return $db_instance;
    }
}
?>