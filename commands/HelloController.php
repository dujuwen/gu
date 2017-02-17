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
    //这个一周执行一次
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
    //./yii hello/c
    public function actionC()
    {
//         $change_url = 'http://web.sqt.gtimg.cn/q=sh600650?r=0.2256620838672143';
//         'http://qt.gtimg.cn/q=s_sh600650'; //最终成交
//         'http://qt.gtimg.cn/r=0.6789q=sh600650'; //实时数据
//         $change_url = 'http://qt.gtimg.cn/r=0.5693976059187198q=s_sz000659';

        set_time_limit(0);
        $intervalTime = 60; //单位秒
        while (true) {
            $hms = date('H:i:s');
            if ($hms > '15:00:00') {
                break;
            }

            if (($hms >= '09:30:00' && $hms <= '11:30:00') || ($hms >= '13:00:00' && $hms <= '15:00:00')) {
                $codes = GuMonitor::find()->select('code')->where(['status' => GuMonitor::STATUS_NORMAL])->asArray()->column();
                foreach ($codes as $code) {
                    $zjc = $this->getAllData($code); //增加仓数据
                    if (is_array($zjc) && count($zjc)) {
                        $model = new GuChange1();
                        $model->code = $code;
                        $model->z_j_c = floatval($zjc[3]);
                        $model->current_date = time();
                        $model->current_date_ = date('Y-m-d');

                        //key 1名称, 2代码, 3当前价格, 4涨跌额, 5涨跌百分百, 6成交手数(手), 7成交额(万)
                        $realData = $this->getTodayChange($code); //成交数据
                        if (is_array($realData) && count($realData)) {
                            $model->deal_count = floatval($realData[6]); //成交量(手)
                            $model->deal_num = floatval($realData[7]); //成交额(万)
                        }

                        //key 3当前价格, 4左收, 5今开, 31价格波动值, 32价格波动比例, 33最高, 34最低, 43振幅
                        $realData2 = $this->getTodayChange2($code); //价格数据
                        if (is_array($realData2) && count($realData2)) {
                            $model->current = floatval($realData2[3]); //当前价格
                            $model->rate = floatval($realData2[32]); //当前浮动百分比
                            $model->yesterday = floatval($realData2[4]); //左收
                            $model->today = floatval($realData2[5]); //今开
                            $model->max = floatval($realData2[33]); //最高
                            $model->min = floatval($realData2[34]); //最低
                            $model->amplitude = floatval($realData2[43]); //振幅
                        }

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
    
                echo 'current time:' . $hms . PHP_EOL;
            }

            $left = $intervalTime - time() % 60;
            break;
            sleep($left);
        }
    }

    //获得主力增减仓 
    //数组的key=3是增加仓
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

    //获得数据类似v_s_sh600425="1~青松建化~600425~7.04~0.37~5.55~1538364~106235~~97.07";
    //数组key 1名称, 2代码, 3当前价格, 4涨跌额, 5涨跌百分百, 6成交手数(手), 7成交额(万)
    private function getTodayChange($code) {
        //http://qt.gtimg.cn/r=0.988934817751348q=s_sh600425 //简单数据url
        try {
            $rand = mt_rand() / mt_getrandmax();
            $tmp = StringHelper::startsWith($code, '6') ? 'sh' : 'sz';
            $code = $tmp . $code;
            $url = 'http://qt.gtimg.cn/r='. $rand .'&q=s_' . $code;
            $data = Util::curl($url);
            $data = iconv('GBK', 'utf-8', $data);
            if ($data) {
                $data = explode('~', $data);
                return $data;
            }
        } catch (\Exception $e) {
            return [];
        }

        return [];
    }

    //获得类似数据v_sh600149="1~廊坊发展~600149~22.88~23.17~23.18~109920~48567~61353~22.88~51~22.87~250~22.86~108~22.85~854~22.84~73~22.90~510~22.92~101~22.93~7~22.94~1~22.95~110~
    //11:30:02/22.88/12/S/27456/15178|11:29:58/22.88/24/S/54912/15172|11:29:56/22.90/1/B/2290/15163|11:29:49/22.90/11/B/25190/15151|11:29:47/22.88/500/S/1144261/15147|11:29:37/22.92/2/B/4584/15129~20170217113553~
    //-0.29~-1.25~23.38~22.81~22.88/109908/252957682~109920~25299~2.89~~~23.38~22.81~2.46~86.98~86.98~44.98~25.49~20.85~0.83";
    //key 3当前价格, 4左收, 5今开, 31价格波动值, 32价格波动比例, 33最高, 34最低
    private function getTodayChange2($code) {
        //http://web.sqt.gtimg.cn/q=sh600149?r=0.0756269266558307 //详细数据url
        try {
            $rand = mt_rand() / mt_getrandmax();
            $tmp = StringHelper::startsWith($code, '6') ? 'sh' : 'sz';
            $code = $tmp . $code;
            $url = 'http://web.sqt.gtimg.cn/q='. $rand .'&q=' . $code;
            $data = Util::curl($url);
            $data = iconv('GBK', 'utf-8', $data);
            if ($data) {
                $data = explode('~', $data);
                return $data;
            }
        } catch (\Exception $e) {
            return [];
        }

        return [];
    }
}
