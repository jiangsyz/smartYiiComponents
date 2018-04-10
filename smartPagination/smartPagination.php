<?php
//分页
namespace common\components\smartPagination;
use Yii;
use yii\base\SmartException;
use yii\base\Component;
//====================================================
class smartPagination extends Component{
	public function getData($query,$pageSize,$pageNum){
		//转换
		$pageSize=(int)$pageSize;
		$pageNum=(int)$pageNum;
		//校验
		if($pageSize<1) throw new SmartException("error pageSize");
		if($pageNum<1) throw new SmartException("error pageNum");
		//获取总数
		$totalCount=(int)$query->count();
		//获取总页数
		$totalPageNum=ceil($totalCount/$pageSize);
		//确定当前页数
		$pageNum=$pageNum>$totalPageNum?$totalPageNum:$pageNum;
		//确定起始记录
		$start=($pageNum-1)*$pageSize;
		//获取数据
		$objs=$query->offset($start)->limit($pageSize)->all();
		//返回
		return array('totalCount'=>$totalCount,'totalPageNum'=>$totalPageNum,'pageSize'=>$pageSize,'pageNum'=>$pageNum,'objs'=>$objs);
	}
	//====================================================
	public function getDataBySql($className,$sql,$pageSize,$pageNum){
		//转换
		$pageSize=(int)$pageSize;
		$pageNum=(int)$pageNum;
		//校验
		if($pageSize<1) throw new SmartException("error pageSize");
		if($pageNum<1) throw new SmartException("error pageNum");
		//获取query
		$query=$className::findBySql($sql);
		//获取总数
		$totalCount=(int)$query->count();
		//获取总页数
		$totalPageNum=ceil($totalCount/$pageSize);
		//确定当前页数
		$pageNum=$pageNum>$totalPageNum?$totalPageNum:$pageNum;
		//确定起始记录
		$start=($pageNum-1)*$pageSize;
		//更新sql
		$sql.=" LIMIT {$start},{$pageSize}";
		//获取数据
		$objs=$className::findBySql($sql)->all();
		//返回
		return array('totalCount'=>$totalCount,'totalPageNum'=>$totalPageNum,'pageSize'=>$pageSize,'pageNum'=>$pageNum,'objs'=>$objs);
	}
}