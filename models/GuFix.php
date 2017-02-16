<?php

namespace app\models;

use app\models\base\BaseGuFix;

class GuFix extends BaseGuFix {

    const TYPE_SH = 'sh'; //上证
    const TYPE_SZ = 'sz'; //深证
    
    public static $types = [
        self::TYPE_SH => 1,
        self::TYPE_SZ => 2
    ];

    public static $types2 = [
        1 => '上证',
        2 => '深证',
    ];

    //返回形式:600149(廊坊发展)
    public static function getNameByCode($code) {
        $model = self::findOne(['code' => $code]);
        return $model ? ($code . '(' . $model->name . ')') : $code; 
    }
}