<?php
namespace models;

class Goods extends Model
{
    // 设置这个模型对应的表
    protected $table = 'goods';
    // 设置允许接收的字段
    protected $fillable = ['goods_name','logo','is_on_sale','description','cat1_id','cat2_id','cat3_id','brand_id'];

    // 添加、修改之前执行
    public function _before_write()
    {
        $this->_delete_logo();
        // 实现上传图片的代码
        $uploader = \libs\Uploader::make();
        $logo = '/uploads/'.$uploader->upload('logo','goods');
        // $this->data ：将要插入到数据库中的数据（数组）
        // 把 logo 加到数组中，就可以插入到数据库
        $this->data['logo'] = $logo;
    }

    // 删除之前被调用（钩子函数：定义好之后自动被调用）
    public function _before_delete()
    {
        $this->_delete_logo();
    }

    protected function _delete_logo()
    {
        if(isset($_GET['id']))
        {
            // 先从数据库中取出原LOGO
            $ol = $this->findOne($_GET['id']);
            // 删除
            @unlink(ROOT.'public'.$ol['logo']);
        }
    }

    // 添加、修改之后执行
    public function _after_write()
    {
        $stmt = $this->_db->prepare("insert into goods_attribute(attr_name,attr_value,goods_id) values(?,?,?)");
        // 循环每一个属性,插入到属性表
        foreach($_POST['attr_name'] as $k => $v)
        {
            $stmt->execute([
                $v,
                $_POST['attr_value'][$k],
                $this->data['id'],
            ]);
        }

        // 商品图片
        $uploader = \libs\Uploader::make();

        $stmt = $this->_db->prepare("insert into goods_image(goods_id,path) values(?,?)");

        $_tmp = [];
        // 循环图片
        foreach($_FILES['image']['name'] as $k => $v)
        {
            // 拼出每张图片需要的数组
            $_tmp['name'] = $v;
            $_tmp['type'] = $_FILES['image']['type'][$k];
            $_tmp['tmp_name'] = $_FILES['image']['tmp_name'][$k];
            $_tmp['error'] = $_FILES['image']['error'][$k];
            $_tmp['size'] = $_FILES['image']['size'][$k];

            // 放到$_FILES数组中
            $_FILES['tmp'] = $_tmp;

            $path = '/uploads/'.$uploader->upload('tmp', 'goods');

            // 执行sql
            $stmt->execute([
                $this->data['id'],
                $path,
            ]);

        }

        // SKU
        $stmt = $this->_db->prepare("INSERT INTO goods_sku
        (goods_id,sku_name,stock,price) VALUES(?,?,?,?)");

        foreach($_POST['sku_name'] as $k => $v)
        {
            $stmt->execute([
                $this->data['id'],
                $v,
                $_POST['stock'][$k],
                $_POST['price'][$k],
            ]);
        }
    }



}