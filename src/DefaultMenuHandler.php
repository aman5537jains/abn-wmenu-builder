<?php

namespace Harimayco\Menu;

use AbnCms\RolesPermission\PermissionService;
use Aman5537jains\AbnCms\Lib\Permission;

use Aman5537jains\AbnCms\Lib\Sidebar\Sidebar;
use Aman5537jains\AbnCms\Lib\Sidebar\SidebarItem;
use Aman5537jains\AbnCms\Models\CmsManagement;
use Harimayco\Menu\Models\MenuItems;
use Harimayco\Menu\Models\Menus;

class DefaultMenuHandler {
    public $row =  [];
    public $config =  [];
    public $name = "";
    public $icon = "";
    public $link = "";
    public $linkType = "";
    public $permissions = [];
    function __construct($row,$config=[]){

        $this->row = $row;
        $this->name = $row->label;
        $this->icon = $row->icon;
        $this->link = $row->link;
        $this->linkType = $row->link_type;
        $this->permissions = json_decode($row->permissions);
        $this->config = (array)$config;

    }

    function getConfig($name,$default=""){
        return isset($this->config[$name])?$this->config[$name]:$default;
    }

    function getLink(){
        if(empty($this->link)){
            return "javascript:;";
        }
        if($this->linkType == "ROUTE_NAME"){

            return route($this->link,(array)$this->getConfig('params',[]));
        }
        else if($this->linkType == "CMS_PAGE"){
            return url("pages/".CmsManagement::find($this->link)->slug);
        }
        else if($this->linkType == "CUSTOM"){
            return $this->link;
        }
        else if($this->linkType == "SYSTEM_URL"){
            return url($this->link);
        }
    }
    function hasChilds(){
        $count = MenuItems::where("parent",$this->row->id)->count();
        return $count>0;
    }

    function renderChilds(){
        $html = '';
        $childs = MenuItems::where("parent",$this->row->id)->get();
        foreach($childs as $child){

            $class =  $child->menu_handler==null? DefaultMenuHandler::class:$child->menu_handler;
            $menuHandler =  new $class($child,json_decode($child->menu_handler_config));
            $html.=$menuHandler->render();
        }

        return $html;
    }

    function hasPermission(){
        $hasPermission = true;
        if($this->permissions){
            foreach($this->permissions as $permission){
                if(!PermissionService::has($permission->module,$permission->action)){
                    $hasPermission =false;
                    break;
                }
            }
        }
        return $hasPermission;
    }

    function linkHtml(){
        $link =$this->getLink();
        return <<<ITEM
            <li class="daside-item">
                <a href="$link">
                    $this->icon
                    <span>$this->name</span>
                </a>
            </li>
        ITEM;
    }
    function parentHtml($childs){
        return "<li class='daside-item has-child'> <a href='#'>
                     <span>$this->name</span>
                 </a><ul class='dropdown-menu' >$childs</ul><li>";
    }

    function render(){


        $list = "";
        if(!$this->hasChilds() && $this->hasPermission()){
            $list =  $this->linkHtml();
        }
        else{
            $childs = $this->renderChilds();

            if(!empty($childs))
            $list =  $this->parentHtml($this->renderChilds());
            else
            $list='';
        }

        return $list;
    }


}
