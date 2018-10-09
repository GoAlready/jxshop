<?php
namespace models;

class Admin extends Model
{
    // 设置这个模型对应的表
    protected $table = 'admin';
    // 设置允许接收的字段
    protected $fillable = ['username','password'];

    // 添加之前自动被执行
    public function _before_write()
    {
        $this->data['password'] = md5($this->data['password']);
    }

    // 添加、修改之后自动执行
    protected function _after_write()
    {

        $id = isset($_GET['id']) ? $_GET['id'] : $this->data['id'];

        // 删除原来的权限
        $stmt = $this->_db->prepare('delete from admin_role where admin_id=?');
        $stmt->execute([
            $id,
        ]);
        
        // 重新添加新勾选的权限
        $stmt = $this->_db->prepare("insert into admin_role(admin_id,role_id) values(?,?)");
        // 循环所有勾选的权限插入到中间表
        foreach($_POST['role_id'] as $v)
        {
            $stmt->execute([
                $id,
                $v,
            ]);
        }
    }

    public function login($username,$password)
    {
        $stmt = $this->_db->prepare('select * from admin where username = ? and password = ?');
        $stmt->execute([
            $username,
            md5($password),
        ]);
        $info = $stmt->fetch(\PDO::FETCH_ASSOC);
        if($info)
        {
            $_SESSION['id'] = $info['id'];
            $_SESSION['username'] = $info['username'];
            // 查看该管理员是否有一个角色ID=1
            $stmt = $this->_db->prepare('SELECT count(*) FROM admin_role WHERE role_id = 1 AND admin_id = ?');
            $stmt->execute([
                $_SESSION['id']
            ]);
            $c = $stmt -> fetch(\PDO::FETCH_COLUMN);
            if($c>0)
            {
                $_SESSION['root'] = true;
            }
            else
            {
                // 把管理员有权访问的路径保存到session中
                $_SESSION['url_path'] = $this->getUalPath($_SESSION['id']);
            }
            
        }
        else
        {   
            throw new \Exception('用户名或者密码错误!');
        }
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
    }

    public function getUalPath($adminId)
    {
        $sql = "SELECT c.url_path FROM admin_role a LEFT JOIN role_privilege b ON a.role_id = b.role_id LEFT JOIN privilege c ON b.pri_id = c.id WHERE a.admin_id = ? AND c.url_path != '' ";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([$adminId]);
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // 把二维数组转成一维数组
        $_ret = [];
        foreach($data as $v)
        {
            // 判断是否有多个url(包含,)
            if(strpos($v['url_path'],',') === FALSE)
            {
                // 如果没有,就直接拿过来
                $_ret[] = $v['url_path'];
            }
            else
            {
                // 如果有,就转成数组
                $_tt = explode(',',$v['url_path']);
                // 把转完之后的数组合并到一维数组中
                $_ret = array_merge($_ret,$_tt);
            }
        }
        return $_ret;
    }

}