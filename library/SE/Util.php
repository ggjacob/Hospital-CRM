<?php

class SE_Util
{
    public static function getValueFromInput($data, $key) {
        if (is_array($data) && isset($data[$key])) {
            return $data[$key];
        } else if (is_object($data) && isset($data->{$key})) {
            return $data->{$key};
        }
        
        return null;
    }
    
    /**
     * @param String $string
     * @param Doctrine\DBAL\Connection DBAL Connection $connection Doctrine DBAL
     * connection.
     */
    public static function escape($string, Doctrine\DBAL\Connection $connection = null) {
        if (null === $connection) {
            $connection = Zend_Registry::get('doctrine')->getConnection();
        }
        
        return $connection->quote($string);          
    }
}