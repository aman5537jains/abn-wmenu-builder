<?php

use AbnCms\RolesPermission\PermissionService;
use Aman5537jains\AbnCms\Lib\AbnCms;
use Aman5537jains\AbnCms\Lib\Theme\ScriptLoader;
use Harimayco\Menu\Facades\Menu;

Route::group(['middleware' => ["web","auth"]+config('menu.middleware')], function () {
    //Route::get('wmenuindex', array('uses'=>'\Harimayco\Menu\Controllers\MenuController@wmenuindex'));
    $path = rtrim(config('menu.route_path'));

    Route::get($path . '/index', function (){
        PermissionService::hasOrAbort("settings","view");

        return  Menu::scripts()."<h2>Menu Builder</h2>".Menu::render();
        return  AbnCms::getActiveTheme("BACKEND_ACTIVE_THEME")
        ->addScripts([
            (new  ScriptLoader(Menu::scripts()))->raw(true)])

        ->setPageContent( "<h2>Menu Builder</h2>".Menu::render())
        ->render();
    })->name("menu-builder.index");

    Route::post($path . '/addcustommenu', array('as' => 'haddcustommenu', 'uses' => '\Harimayco\Menu\Controllers\MenuController@addcustommenu'));
    Route::post($path . '/deleteitemmenu', array('as' => 'hdeleteitemmenu', 'uses' => '\Harimayco\Menu\Controllers\MenuController@deleteitemmenu'));
    Route::post($path . '/deletemenug', array('as' => 'hdeletemenug', 'uses' => '\Harimayco\Menu\Controllers\MenuController@deletemenug'));
    Route::post($path . '/createnewmenu', array('as' => 'hcreatenewmenu', 'uses' => '\Harimayco\Menu\Controllers\MenuController@createnewmenu'));
    Route::post($path . '/generatemenucontrol', array('as' => 'hgeneratemenucontrol', 'uses' => '\Harimayco\Menu\Controllers\MenuController@generatemenucontrol'));
    Route::post($path . '/updateitem', array('as' => 'hupdateitem', 'uses' => '\Harimayco\Menu\Controllers\MenuController@updateitem'));
});
