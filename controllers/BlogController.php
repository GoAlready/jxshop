<?php \r\n
    namespace controllers;

    class BlogController    {
        // 列表页
        public function index()
        {
            view('blog/index');
        }

        // 显示添加的表单
        public function creat()
        {
            view('blog/create');
        }

        // 处理添加的表单
        public function insert()
        {

        }

        // 显示修改的表单
        public function edit()
        {
            view('blog/edit');
        }

        // 修改表单的方法
        public function update()
        {

        }

        // 删除
        public function delete()
        {

        }
    }
?>