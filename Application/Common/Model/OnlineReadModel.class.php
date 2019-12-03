<?php
namespace Common\Model;
use Common\Model\BaseModel;
/**
 * 菜单操作model
 */
class OnlineReadModel extends BaseModel{

	public function getState()
	{
		$userid = $_SESSION['user']['id'];
		$read = M('online_read') -> field('pid') -> where("userid='$userid' and state=0") -> select();
		foreach ($read as $key => $value)
		{
			foreach ($value as $k => $v)
			{
				$aread[$key] = $v;
			}
		}
		// var_dump($aread);die;die;
		return $aread;
	}

	public function changeState($post,$huawu)
	{
		$res = "(" . implode(',',$post) . ")";
		$where = 'pid IN' . $res;
		$userid = $_SESSION['user']['id'];
		$mo = M('online_read');
		$mo -> state = 1;
		$state = $mo -> where($where . " and state=0 and userid='$userid'") -> save();
		$user = M('users') -> field('id') -> where("username='$huawu'") -> find();
		// echo $huawu;
		// var_dump($userid);die;
		$user = $user['id'];
		foreach ($post as $key => $value)
		{
			$add['userid'] = $user;
			$add['pid'] = $value;
			$mo -> add($add);
		}
		if($state)
		{
			return ture;
		}
		else
		{
			return false;
		}
	}

	public function changesState($id)
	{
		$userid = $_SESSION['user']['id'];
		$where ="pid='$id' and userid='$userid' and state=0";
		$m = M('online_read');
		$m -> state = 1;
		$state = $m -> where($where) -> save();
		if($state)
		{
			return ture;
		}
		else
		{
			return false;
		}
	}

}
