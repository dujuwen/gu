<?php

namespace app\models;

use app\models\base\BaseGuMonitor;

class GuMonitor extends BaseGuMonitor {
    const STATUS_NORMAL = 1;
    const STATUS_DELETE = 2;
    
    public static $status = [1 => '正常', 2 => '删除'];
}