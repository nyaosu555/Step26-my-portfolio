<?php

namespace App\Enums;
/**
 * メニュージャンルのタイプを定義するEnum
 */
enum MenuType: int {
    case Main = 1;      //主菜
    case SideA = 2;     //副菜A
    case SideB = 3;     //副菜B
}
