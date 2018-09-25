<?php

    namespace libs;

    class Db
    {

        private $_pdo;

        private static $_obj = null;

        private function __clone(){}

        private function __construct()
        {

            $this->_pdo = new PDO('mysql:host=localhost;dbname=jxshop','root','123456');

            $this->_pdo->exec("set names utf8");

        }

        public static function make()
        {
            if(self::$_obj === null)
            {
                self::$_obj = new self;

            }
            return self::$_obj;
        }

    }

?>