<?php
namespace models;

class Role extends Model
{
    // 设置这个模型对应的表
    protected $table = 'role';
    // 设置允许接收的字段
    protected $fillable = ['role_name'];

    // 添加、修改之后自动执行
    protected function _after_write()
    {
        $stmt = $this->_db->prepare("insert into role_privilege(pri_id,role_id) values(?,?)");
        // 循环所有勾选的权限插入到中间表
        foreach($_POST['pri_id'] as $v)
        {
            $stmt->execute([
                $v,
                $this->data['id'],
            ]);
        }
    }

    protected function _before_delete()
    {
        $stmt = $this->_db->prepare("delete from role_privilege where role_id=?");
        $stmt->execute([
            $_GET['id'],
        ]);
    }
}