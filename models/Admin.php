<?php
namespace models;

class Admin extends Model
{
    // 设置这个模型对应的表
    protected $table = 'admin';
    // 设置允许接收的字段
    protected $fillable = ['username','password'];
}