<?php

namespace App\References\Menu;

interface InterfaceMenu {

    public function getMenus() : array;
    public function sidebarMenu() : array;

}