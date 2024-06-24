<?php

namespace Harimayco\Menu;

use Aman5537jains\AbnCms\Lib\Permission;
use Aman5537jains\AbnCms\Lib\Plugin;
use Aman5537jains\AbnCms\Lib\Sidebar\Sidebar;
use Aman5537jains\AbnCms\Lib\Sidebar\SidebarItem;

class AbnWPMenuPlugin extends Plugin{


    function getName()
    {
        return "Menu Builder";
    }
    static  function getKey()
    {
        return "wmenu";
    }
    public static function permissions(){
        return new Permission(self::getKey());
    }

    public static function sidebar(){

        return new Sidebar("Menu Builder",[

            new SidebarItem("Menu Builder",route("menu-builder.index"),"",function($permissions){
                return isset($permissions["contact-form__view"]);
            }),


        ]);
    }

    public function onActivate()
    {

        $this->addBackendSidebar()->push(self::getKey(),[self::class,"sidebar"]);
        $this->addPermission(self::getKey());



    }
    public function onInActivate()
    {
        $this->removeBackendSidebar(self::getKey());
        $this->removePermission(self::getKey());

    }

    function install()
    {

    }
    function unInstall()
    {

    }

    function render(){

    }

}
