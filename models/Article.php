<?php
namespace models;

class Article extends Model
{
    // 设置这个模型对应的表
    protected $table = 'article';
    // 设置允许接收的字段
    protected $fillable = ['title','content','link','article_category_id'];
}