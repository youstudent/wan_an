<?php
/**
 * Created by PhpStorm.
 * User: harlen-mac
 * Date: 2017/5/28
 * Time: 上午10:00
 */

namespace common\models;


class Tree
{
    //节点图
    static $structure_tree = [
        1 => ['left' => 2, 'middle' => 3, 'right' => 4],
        2 => ['left' => 5, 'middle' => 8, 'right' => 11],
        3 => ['left' => 6, 'middle' => 9, 'right' => 12],
        4 => ['left' => 7, 'middle' => 10, 'right' => 13],
        5 => ['left' => 14, 'middle' => 23, 'right' => 32],
        6 => ['left' => 15, 'middle' => 24, 'right' => 33],
        7 => ['left' => 16, 'middle' => 25, 'right' => 34],
        8 => ['left' => 17, 'middle' => 26, 'right' => 35],
        9 => ['left' => 18, 'middle' => 27, 'right' => 36],
        10 => ['left' => 19, 'middle' => 28, 'right' => 37],
        11 => ['left' => 20, 'middle' => 29, 'right' => 38],
        12 => ['left' => 21, 'middle' => 30, 'right' => 39],
        13 => ['left' => 22, 'middle' => 31, 'right' => 40],
    ];


    static $structure = [
        1 => ['node'=>0, 'position'=> 'top'],
        2 => ['node' => 1, 'position' => 'left'],
        3 => ['node' => 1, 'position' => 'middle'],
        4 => ['node' => 1, 'position' => 'right'],
        5 => ['node' => 2, 'position' => 'left'],
        6 => ['node' => 3, 'position' => 'left'],
        7 => ['node' => 4, 'position' => 'left'],
        8 => ['node' => 2, 'position' => 'middle'],
        9 => ['node' => 3, 'position' => 'middle'],
        10 => ['node' => 4, 'position' => 'middle'],
        11 => ['node' => 2, 'position' => 'right'],
        12 => ['node' => 3, 'position' => 'right'],
        13 => ['node' => 4, 'position' => 'right'],
        14 => ['node' => 5, 'position' => 'left'],
        15 => ['node' => 6, 'position' => 'left'],
        16 => ['node' => 7, 'position' => 'left'],
        17 => ['node' => 8, 'position' => 'left'],
        18 => ['node' => 9, 'position' => 'left'],
        19 => ['node' => 10, 'position' => 'left'],
        20 => ['node' => 11, 'position' => 'left'],
        21 => ['node' => 12, 'position' => 'left'],
        22 => ['node' => 13, 'position' => 'left'],
        23 => ['node' => 5, 'position' => 'middle'],
        24 => ['node' => 6, 'position' => 'middle'],
        25 => ['node' => 7, 'position' => 'middle'],
        26 => ['node' => 8, 'position' => 'middle'],
        27 => ['node' => 9, 'position' => 'middle'],
        28 => ['node' => 10, 'position' => 'middle'],
        29 => ['node' => 11, 'position' => 'middle'],
        30 => ['node' => 12, 'position' => 'middle'],
        31 => ['node' => 13, 'position' => 'middle'],
        32 => ['node' => 5, 'position' => 'right'],
        33 => ['node' => 6, 'position' => 'right'],
        34 => ['node' => 7, 'position' => 'right'],
        35 => ['node' => 8, 'position' => 'right'],
        36 => ['node' => 9, 'position' => 'right'],
        37 => ['node' => 10, 'position' => 'right'],
        38 => ['node' => 11, 'position' => 'right'],
        39 => ['node' => 12, 'position' => 'right'],
        40 => ['node' => 13, 'position' => 'right'],
    ];
}