<?php

    namespace models;

    class Model
    {

        protected $_db;
        // 操作的表名
        protected $table;        
        // 表单中的数据
        protected $data;

        /**
         * $data = [
         *   'title'=>'xxxx',
         *   'content' => 'xxxx',
         *   'is_show'=> 'y'
         * ];
         */

        public function __construct()
        {
            $this->_db = \libs\Db::make();
        }

        public function insert()
        {
            $keys = [];
            $values = [];
            $token = [];

            foreach($this->data as $k => $v)
            {
                $keys[] = $k;
                $values[] = $v;
                $token[] = '?';
            }

            $keys = implode(',',$keys);
            $token = implode(',',$token);

            $sql = "insert into {$this->table}($keys) values($token)";

            $stmt = $this->_db->prepare($sql);
            return $stmt->execute($values);
        }

        public function update()
        {

        }

        public function delete()
        {
            
        }

        public function findAll()
        {
            
        }

        public function findOne()
        {
            
        }

        // 接收表单中的数据
        public function fill($data)
        {
            // var_dump($data);
            foreach($data as $k => $v)
            {
                if(!in_array($k,$this->fillable))
                {
                    unset($data[$k]);
                }
            } 
            $this->data = $data; 
        }
    }

?>