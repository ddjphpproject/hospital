<?php
namespace Common\Model;
use Common\Model\BaseModel;

class PatientModel extends BaseModel{

/**
 * [lineChartData 折线图数据处理]
 * @param  [type] $data     [array]
 * @param  [type] $nameData [array]
 * @param  [type] $number   [int or string]
 * @return [type]           [json]
 */
    public function lineChartData($data,$nameData,$number)
    {
        $now = strtotime(date("Y-m-d"));
        $start = date('Y-m-01', $now);//本月一号日期
        $end = date('Y-m-d', strtotime("-1 day"));//前一天日期
        $endt = date('Y-m-d', strtotime("$start +1 month -1 day"));//本月最后一天日期
        $month_day = strtotime($start);//本月一号时间戳
        $daynum = getDateRange($start,$endt);
        $month = getDateFromRange($start,$end);
        /*循环将时间戳转化为日期格式*/
        foreach ($data as $key => $value)
        {
            $data[$key]['name'] = $value['name'];
            $data[$key]['type'] = 'line';
            if(empty($value['data']))
            {
                $data[$key]['data'] = $value['data'];
            }
            else
            {
                foreach ($value['data'] as $k => $v)
                {
                    $data[$key]['data'][$k] = date('Y-m-d',$v['yuyuetime']);
                }
            }
        }
        /*将相同日期的条数合并*/
        foreach ($data as $key => $value)
        {
            $data[$key]['name'] = $value['name'];
            $data[$key]['type'] = 'line';
            $data[$key]['data'] = array_count_values($value['data']);
        }
        
        /*预约方式表格数据*/
        foreach ($data as $key => $value)
        {
            foreach($month as $k2 =>$v2)
            {
                $v2['count'] = 0;
                foreach($value['data'] as $k =>$v)
                {
                    if($v2['time'] == $k)
                    {
                        $v2['count'] = $v;
                    }
                }
            $tableData[$key]['count'][] = $v2['count'];
            }
            $tableData[$key]['name'] = $value['name'];
            $tableData[$key]['all'] = $value['all'];
        }

        /*预约方式表格数据*/
        /*将所有预约方式的数据与每天比较*/
        foreach ($data as $key => $value)
        {
            foreach($month as $k2 =>$v2)
            {
                $v2['count'] = 0;
                foreach($value['data'] as $k =>$v)
                {
                    if($v2['time'] == $k)
                    {
                        $v2['count'] = $v;
                    }
                }
            $data[$key]['data']['count'][] = $v2['count'];
            }

            $data[$key]['name'] = $value['name'];
            $data[$key]['type'] = 'line';
        }
        $dlength = count($daynum);
        $mlength = count($month);
        /*计算空格的长度*/
        $length = $dlength - $mlength;

        /*总计数据(最后一行数据)*/
        foreach ($month as $key => $value)
        {
            $allTime[$key] = strtotime($value['time']);
        }
        switch ($number) {
            case 1:
                foreach ($allTime as $key => $value)
                {
                    $end = $value+86400;
                    $where = "recycle=0 and yuyuetime>'$value' and yuyuetime<'$end'";                    $rowAll[] = M('patient') -> where($where) -> count();
                }
                $total = M('patient') -> where("recycle=0 and yuyuetime>'$month_day' and yuyuetime<'$now'") -> count();
                break;
            case 2:
                foreach ($allTime as $key => $value)
                {
                    $end = $value+86400;
                    $where = "recycle=0 and yuyuetime>'$value' and yuyuetime<'$end' and diagnosis=1 and consumption=1";
                    // echo $where;die;
                    $rowAll[] = M('patient') -> where($where) -> count();
                }
                $total = M('patient') -> where("recycle=0 and yuyuetime>'$month_day' and yuyuetime<'$now' and diagnosis=1 and consumption=1") -> count();
                break;
            case 3:
                foreach ($allTime as $key => $value)
                {
                    $end = $value+86400;
                    $rowAll[] = M('patient as p') -> join('yuyue_online_order as o on p.ordernum=o.ordernum')-> where("p.recycle=0 and p.yuyuetime>'$value' and p.yuyuetime<'$end' and o.paystate=1 and p.diagnosis=1 and consumption=1") -> count();
                }
                $total = M('patient as p') -> join('yuyue_online_order as o on p.ordernum=o.ordernum')-> where("p.recycle=0 and p.yuyuetime>'$month_day' and p.yuyuetime<'$end' and o.paystate=1 and p.diagnosis=1 and consumption=1") -> count();
                break;
            case 4:
                foreach ($allTime as $key => $value)
                {
                    $end = $value+86400;
                    $where = "recycle=0 and time>'$value' and time<'$end'";                    $rowAll[] = M('patient') -> where($where) -> count();
                }
                $total = M('patient') -> where("recycle=0 and time>'$month_day' and time<'$now'") -> count();
                break;
        }
        /*总计数据(最后一行数据)*/

        /*组装表格参数*/
        $table .= "<table><tr><td></td>";
        foreach ($daynum as $key => $value)
        {
            $table .= "<td>{$value}号</td>";
        }
        $table .= "<td>总计</td></tr>";

        foreach ($tableData as $k => $v)
        {
            $table .= "<tr><td>{$v['name']}</td>";
            foreach ($v['count'] as $k1 => $v1)
            {
                $table .= "<td>{$v1}</td>";
            }
            for ($i=0; $i < $length ; $i++)
            {
                $table .= "<td></td>";
            }
            $table .= "<td>{$v['all']}</td></tr>";

        }
        $table .= "<tr><td>总计</td>";
        foreach ($rowAll as $key => $value)
        {
            $table .= "<td>$value</td>";
        }
        for ($i=0; $i < $length ; $i++)
        {
             $table .= "<td></td>";
        }
        $table .= "<td>$total</td></tr>";
        $table .= "</table>";
        /*组装表格参数*/

        /*将所有预约方式每日数据分解为逗号相连并除去以数字为键的数据*/
        foreach ($data as $key => $value)
        {
            $rdata[$key]['name'] = $value['name'];
            $rdata[$key]['type'] = 'line';
            $rdata[$key]['data'] = implode(',',$value['data']['count']);
        }
        /*将data转化为数组*/
        foreach ($rdata as $key => $value)
        {
            $rdata[$key]['data'] = explode(',',$value['data']);
        }
        /*将data中的数据转换为int类型*/
        foreach ($rdata as $key => $value)
        {
            foreach ($value['data'] as $k => $v)
            {
                $rdata[$key]['data'][$k] = intval($v);
            }
        }
        // echo "<pre/>";
        // var_dump($rdata);die;
        $return['name'] = json_encode($nameData);
        $return['data'] = json_encode($rdata);
        $return['table'] = $table;
        return $return;
    }

/**
 * [barChartData 柱状图数据处理]
 * @param  [type] $data     [array]
 * @param  [type] $nameData [array]
 * @return [type]           [json]
 */
    public function barChartData($data,$nameData)
    {
        $data = array(
                    array(
                            'name' => '预约',
                            'type'=>'bar',
                            'data' => $data['data']
                        ),
                    array(
                        'name' => '到诊',
                        'type'=>'bar',
                        'data' => $data['all']
                        )
                    );
        foreach ($data as $key => $value)
        {
            foreach ($value['data'] as $k => $v)
            {
                $data[$key]['data'][$k] = intval($v);
            }
        }
        $return['name'] = json_encode($nameData);
        $return['data'] = json_encode($data);
        return $return;
    }

    public function payLineChart($Data)
    {
        foreach ($Data as $key => $value)
        {
            $name = $value['source'] . '-支付';
            $data[$key]['name'] = $name;
            $data[$key]['type'] = 'line';
            $data[$key]['data'] = $value;
        }
        foreach ($data as $key => $value)
        {
            $res[$key]['name'] = $name;
            $res[$key]['type'] = 'line';
            foreach ($value['data'] as $key => $value)
            {
                $res[$key]['data'][$k] = date('Y-m-d',$v['yuyuetime']);
            }
        }
    }


    public function analyzeData()
    {
        $result = $this -> where("recycle=0") -> select();
        return $result;
    }

    public function editShowAnalyze($data)
    {
        $id = $data['id'];
        $result = $this -> where("id='$id'") -> select();
        return $result;
    }

    public function editAnalyze($data)
    {
        $id = $data['id'];
        $result = $this -> where("id='$id'") -> save($data);
        return $result;
    }

    public function deleteAnalyze($id,$data)
    {
        $this -> recycle=1;
        $result = $this -> where("id='$id'") -> save($data);
        return $result;
    }

}
