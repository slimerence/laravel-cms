<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Utils\ContentTool;
use DB;

class Menu extends Model
{
    const TYPE_STATIC_CONTENT  = 1; // 指向静态内容页, 此种类型的menu的link，会被添加 /page , 然后路由定向到 Pages 控制器的view方法
    const TYPE_DYNAMIC_CONTENT = 2; // 指向动态内容页, 不会添加任何前缀，可能需要开发人员自己去创建路由和控制器等

    protected $fillable = [
        'name',
        'name_cn',
        'position',
        'level',
        'parent_id',
        'active',
        'link_to',
        'css_classes',
        'html_tag',
        'extra_html'
    ];

    public $timestamps = false;

    /**
     * 获取Menu的链接
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getMenuUrl(){
        return $this->link_type == self::TYPE_STATIC_CONTENT
            ? url('/page'.$this->link_to)
            : url($this->link_to);
    }

    /**
     * 获取主菜单
     * @return mixed
     */
    public static function getRootMenus(){
        return self::where('level', 1)
            ->where('active',true)
            ->orderBy('position','asc')
            ->orderBy('name','asc')
            ->get();
    }

    /**
     * 获取子菜单
     * @return mixed
     */
    public function getSubMenus(){
        return self::where('parent_id', $this->id)
            ->where('active',true)
            ->orderBy('position','asc')
            ->orderBy('name','asc')
            ->get();
    }

    /**
     * 取得当前菜单的父级菜单
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(){
        return $this->belongsTo(Menu::class,'parent_id');
    }

    /**
     * 取得当前菜单对象的下一级菜单
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(){
        return $this->hasMany(Menu::class,'parent_id');
    }

    /**
     * 返回给定id作为根的菜单树结构
     * @param int $root
     * @return int
     */
    public static function Tree($root = 1){
        $root = self::find($root);
        $root->loadTree();
        return $root;
    }

    /**
     * 利用递归的方式取得菜单的结构树
     */
    public function loadTree(){
        foreach ($this->children as $child) {
            $child->loadTree();
        }
    }

    /**
     * 保存Menu的方法
     * @param array $data
     * @return Integer
     */
    public static function Persistent($data){
        $data = ContentTool::RemoveNewLine($data);

        $data['position'] = empty($data['position']) ? 0 : $data['position'];

        if(!isset($data['id']) || is_null($data['id']) || empty(trim($data['id']))){
            unset($data['id']);
            $menu = self::create(
                $data
            );
            if($menu){
                return $menu->id;
            }else{
                return 0;
            }
        }else{
            $menu = self::find($data['id']);
            unset($data['id']);
            foreach ($data as $field_name=>$field_value) {
                $menu->$field_name = $field_value;
            }
            if($menu->save()){
                return $menu->id;
            }else{
                return 0;
            }
        }
    }

    /**
     * 删除一个目录的方法
     * @param $id
     * @return bool|null
     * @throws \Exception
     */
    public static function Terminate($id){
        $result = false;
        DB::beginTransaction();
        $menu = self::find($id);
        if($menu){
            // 删除所有的子菜单
            self::where('parent_id',$id)->delete();

            // 删除自己
            $result = $menu->delete();
        }

        if($result){
            DB::commit();
        }else{
            DB::rollBack();
        }
        return $result;
    }
}
