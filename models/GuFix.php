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

}