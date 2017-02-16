<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\library\Util;
use yii\helpers\StringHelper;
use app\models\GuFix;
use app\models\GuMonitor;
use app\models\GuChange1;
use app\models\GuRecent;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * 
     * /Users/junwendu/Project/github/gu
     * gu git:(master) ./yii hello/index
     * 
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
        //获取上证和深证指数地址
        $sh_zs_index_url = 'http://qt.gtimg.cn/r=0.3978993779751039q=s_sh000001,s_sz399001';
        //数据格式
        //v_s_sh000001 = "1~上证指数~000001~3212.99~-4.94~-0.15~241507706~25796524~~";
        //v_s_sz399001 = "51~深证成指~399001~10177.25~-87.67~-0.85~184658833~26178875~~";
    }

    //固定数据导入,获取基本code
    //.yii hello/fix
    public function actionFix()
    {
        $fix_url = 'http://stock.gtimg.cn/data/index.php?appn=rank&t=ranka/last&o=1&l=80&v=list_data&p=';

        for ($page = 1; $page < 40; $page++) {
            try {
                $data = Util::curl($fix_url . $page);
                $pos = strpos($data, "data:'");
                $strData = rtrim(substr($data, $pos + 6), "'};");
                $arrData = explode(',', $strData);
                if (is_array($arrData) && count($arrData)) {
                    foreach ($arrData as $idStr) {
                        $code = substr($idStr, 2);
    
                        $model = GuFix::findOne(['code' => $code]);
                        if ($model) {
                            continue;
                        }

                        $model = new GuFix();
                        $model->code = $code;
                        if (StringHelper::startsWith($idStr, GuFix::TYPE_SH)) {
                            $model->type = GuFix::$types[GuFix::TYPE_SH];
                        } else {
                            $model->type = GuFix::$types[GuFix::TYPE_SZ];
                        }
                        $model->save();
                    }
                } else {
                    break;
                }
                $page++;
            } catch (\Exception $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }

    //获取固定信息
    public function actionFixInfo()
    {
        $all = GuFix::find()->select('type,code')->asArray()->all();
        $i = 1;
        foreach ($all as $value) {
            $type = $value['type'];
            $code = $value['code'];
            $model = GuFix::findOne(['code' => $code]);

            if ($model) {
                //名称更新的话一周执行一次
                $name = $this->getCodeName($type, $code);
                $model->name = $name;
                $re = $model->save(); //注意保存了两次!!!!!!
                if ($re) {
                    echo $code . '更新成功, ' . $i . PHP_EOL;
                    $i++;
                }

                //下面是更新总的市值和流通市值
                continue;
                var_dump('下面的一天执行一次');die;

                $data = $this->getChangeData($type, $code);
                if (is_array($data) && count($data)) {
                    //44是流通的
                    //45是总的
                    $total = floatval($data[45]) * 100000000;//单位是亿要计算下
                    if ($total % 100 != 0) {
                        $total = 100 + $total - ($total % 100);
                    }
                    $model->total = $total;

                    $circulation = floatval($data[44]) * 100000000; //单位是亿要计算下
                    if ($circulation % 100 != 0) {
                        $circulation = 100 + $circulation - ($circulation % 100);
                    }
                    $model->circulation = $circulation;

                    $bigRate = floatval($this->getBigPercent($code)) / 100;
                    $model->hand_rate = $bigRate;

                    $hand_num = ceil($model->circulation  * $bigRate);
                    if ($hand_num % 100 != 0) {
                        $hand_num = 100 + $hand_num - ($hand_num % 100);
                    }
                    $model->hand_num = $hand_num;

                    $model->left_num = $model->circulation - $model->hand_num;
                    $re = $model->save();
                    if ($re) {
                        echo $code . '更新成功, ' . $i . PHP_EOL;
                        $i++;
                    }
                }
            }
        }
    }

    //获取总共的股本数,包括浮动数
    private function getChangeData($type, $code) {
        try {
            $rand = mt_rand() / mt_getrandmax();
            $tmp = ($type == 1) ? 'sh' : 'sz';
            $gu_ben_info = 'http://qt.gtimg.cn/'. $rand .'q=' . $tmp . $code;
            $data = Util::curl($gu_ben_info);
            $arrData = explode('~', $data);
            return $arrData;
        } catch (\Exception $e) {
            return [];
        }

        return [];
    }

    //获取大股东持股比例
    private function getBigPercent($code) {
        //股东股份信息
        try {
            $gu_don_info = 'http://stock.finance.qq.com/corp1/stk_holder.php?zqdm=' . $code;
            $data = Util::curl($gu_don_info);
            $percent_pos = strpos($data, 'class="nobor_l fntB"');
            $big_percent_str = substr($data, $percent_pos, 500); //所有大股东持有比例
            preg_match('/([\d\.]*)%/', $big_percent_str , $matches);
            if (is_array($matches) && count($matches) >= 2) {
                $big_percent = $matches[1];
                return $big_percent;
            }
        } catch (\Exception $e) {
            return 0;
        }

        return 0;
    }

    //获取股票名称
    private function getCodeName($type, $code) {
        try {
            $code = (($type == 1) ? 'sh' : 'sz') . $code;
            $code_name_url = 'http://qt.gtimg.cn/q=s_' . $code;
            $data = Util::curl($code_name_url, 0, '', ["charset=UTF-8"]);
            $data = iconv('GBK', 'utf-8', $data);
            if ($data) {
                $arrNames = explode('~', $data);
                return $arrNames[1];
            }
        } catch (\Exception $e) {
            return '';
        }

        return '';
    }

    //获取动态信息
    //./yii hello/change-info
    public function actionChangeInfo()
    {
//         $change_url = 'http://web.sqt.gtimg.cn/q=sh600650?r=0.2256620838672143';
//         'http://qt.gtimg.cn/q=s_sh600650'; //最终成交
//         'http://qt.gtimg.cn/r=0.6789q=sh600650'; //实时数据
//         $change_url = 'http://qt.gtimg.cn/r=0.5693976059187198q=s_sz000659';
        
        $codes = GuMonitor::find()->select('code')->where(['status' => GuMonitor::STATUS_NORMAL])->asArray()->column();
        foreach ($codes as $code) {
            $zjc = $this->getAllData($code);
            if (is_array($zjc) && count($zjc)) {
                $model = new GuChange1();
                $model->code = $code;
                $model->z_j_c = floatval($zjc[3]);
                $model->current_date = time();
                $model->current_date_ = date('Y-m-d');
                $re = $model->save();
                //var_dump($re, $model->getFirstErrors());

                if (date('H:i:s') > '15:00:00') {
                    //每日增减仓情况
                    $recent = new GuRecent();
                    $recent->code = $code;
                    $recent->day = date('Y-m-d');
                    $recent->final_zjc = floatval($zjc[3]);
                    $recent->save();
                }
            }
        }
    }

    //获得主力增减仓 
    // 数组的key=3是增加仓
    private function getAllData($code) {
        try {
            $rand = mt_rand() / mt_getrandmax();
            $tmp = StringHelper::startsWith($code, '6') ? 'sh' : 'sz';
            $code = $tmp . $code;
            $zjc_url = 'http://qt.gtimg.cn/r='. $rand .'&q=ff_' . $code;
            $data = Util::curl($zjc_url);
            if ($data) {
                $data = explode('~', $data);
                return $data;
            }
        } catch (\Exception $e) {
            return 0;
        }

        return 0;
    }

}
