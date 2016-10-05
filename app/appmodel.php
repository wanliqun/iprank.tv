<?php
/**
 * 应用模型类
 * appmodel.php
 *
 * @copyright Copyright (c) 2010-2014 Itbeing (Beijing) Tech co.,Ltd
 * @author itbeing<td@itbeing.com>
 * @package	iprank.co
 * @version 2014-05-08
 */
class AppModel extends Model{
	
	/**
	 * 获取数据库的版本信息
	 * 
	 * @return string
	 */
	public function getDbVersion(){
		return $this->db->adapter.' '.$this->db->version();
	}
	
	/**
	 * 根据外键列表获取关联数据库表的对象值
	 * 
	 * @param array $items		多维数组
	 * @param array $relates	array('categories'=>'category_id')
	 * @return array			return array('__cateogry'=>array())
	 */
	public function getLinkedItems($items,$relates=array()){
		$models = array();
		foreach($items as $key=>$item){
			foreach($relates as $table=>$field){
				if(isset($item[$field]) && !empty($item[$field]) ) $models[$table][] = $item[$field];
			}
		}
		
		if(!empty($models)){
			//查询结果
			$modelRs = array();
			foreach($models as $table=>$values){
				$values = array_unique($values);	//去除重复
				if(!empty($values)){
					$cond = array( 'where' => array('id IN'=>$values), 'table' => $table );
					$rs = $this->find($cond);
					$modelRs[$table] = array();
					foreach($rs as $k=>$v){
						$modelRs[$table][$v['id']] = $v;
					}
				}
			}
			if(!empty($modelRs)){
				foreach($items as $key=>$item){
					foreach($relates as $table=>$field){
						$itemVal = $item[$field];
						$item['__'.$table] = isset($modelRs[$table][$itemVal]) ? $modelRs[$table][$itemVal] : array();
					}
					$items[$key] = $item;
				}
			}
		}
		return $items;
	}
	
	/**
	 * 根据外键列表获取单记录的关联数据库表的对象值
	 * 
	 * @param array $item		数组对象
	 * @param array $relates	array('categories'=>'category_id')
	 * @return array			return array('__cateogry'=>array())
	 */
	public function getLinkedItem($item,$relates=array()){
		foreach($relates as $table=>$field){
			if(isset($item[$field]) && !empty($item[$field])){
				$cond = array( 'where' => array('id'=>$item[$field]), 'table' => $table );
				$item['__'.$table] = $this->find($cond,0);
			}
		}
		return $item;
	}
	
	/**
	 * 复合查询过滤器查询条件语句
	 * filters=>array( 'field'=> array( 'op' => 'like', 'value' => array(), ) );
	 * 
	 * @param array $filters
	 * @param string $table
	 * @return array
	 */
	function searchFilter($filters,$table=null){
		$tableName = $this->getTrueTableName($table);
		$fields = $this->getTableFields( $table );
		$onlyfields = array_keys( $fields );
		/*helper(array('string','datetime'));
		$filters = isset( $param['filters'] ) ? $param['filters'] : array();	//过滤配置
		$selectFields = isset($param['fields']) ? (is_array($param['fields']) ? $param['fields'] : explode(',',$param['_fields']) ) : null;
		$groups = isset($param['group']) ? $param['group'] : null;
		$sorts = isset($param['sort']) ? explode(',',$param['sort']) : null;*/
		$operators = array( '=','!=','>','<','>=','<=','BETWEEN','IN','UNIN','LIKE','UNLIKE', '%LIKE%','%LIKE','LIKE%','%UNLIKE%','%UNLIKE','UNLIKE%');
		$where = array();
		foreach($filters as $field=>$filter){
			$op = strtoupper(trim($filter['op']));
			$value = $filter['value'];
			if( !in_array( $op,$operators ) || !in_array($field,$onlyfields) || $value==='' ) continue;
			$tfield = $field;
			$field = $tableName.'.'.$field;
			if(in_array($op,array('=','!=','IN','UNIN'))){
				$where[$field.' '.$op] = $value;
			}elseif($op=='BETWEEN'){
				$where[$field.' >='] = $value[0];
				$where[$field.' <='] = $value[1];
			}else{
				$fieldType = strtolower($fields[$tfield]['type']);
				$value = is_array($value) ? implode(',',$value) : $value;
				if(false !== strpos($fieldType,'int')) {
					$value = intval($value);
                }elseif(false !== strpos($fieldType,'float') || false !== strpos($fieldType,'double')){
                	$value = floatval($value);
                }
				$where[$field.' '.$op] = $value;
			}
		}
		return $where;
	}
	
	/**
     * 根据表单生成查询条件
     * 进行列表过滤
	 * 
	 * @param array	$data		搜索字段值
	 * @param  string $table	搜索表名称
	 * @param string $logic		逻辑操作符
     * @return array
     */
	function searchWhere($data=null,$table=null,$logic='and'){
		$data = !empty($data) ? $data : $_REQUEST;
		
		$fields = $this->getTableFields( $table );
		$tableName = $this->getTrueTableName($table);
		$where = array('__logic'=> $logic);
		
		foreach( $fields as $field=>$info ) {
			//跳过主键过滤条件
			if( $field==$this->getPk($table)) continue;
			
			if( isset( $data[$field] ) && $data[$field]!='' && $data[$field]!='-1983' ){
				$fieldType = strtolower($info['type']);// 字段类型检查
				$value = $data[$field];
				if(false !== strpos($fieldType,'int')) {
					$value   =  is_array($value) ? $value : intval($value);
                }elseif(false !== strpos($fieldType,'float') || false !== strpos($fieldType,'double')){
                	$value   =  is_array($value) ? $value : floatval($value);
                }
				$where[$tableName.'.'.$field] = $value;
			}
		}
		return $where;
	}
	
	/**
	 * 解析LIKE搜索条件
	 * <code>
	 * searchLike(array('name','content'),'fortest','both'); // name LIKE '%fortest%' OR content LIKE '%fortest%'
	 * </code>
	 * 
	 * @param mixed $sfields 	搜索数据库字段 array or string 
	 * @param string $keyword 	搜索关键字
	 * @param string $stype 	搜索LIKE类型 Both:%kw%,left:'%kw',right:'kw%',none:'kw'
	 * @param string $op 		搜索类型 LIKE or UNLIKE
	 * @param string $table		搜索表名
	 * @param string $logic		搜索逻辑
	 * @return array
	 */
	function searchLike($sfields=null,$keyword=null,$stype='both',$op = 'LIKE',$table=null,$logic='or'){
		$sfields = empty($sfields) ? (isset($_REQUEST['_sfields'])? $_REQUEST['_sfields']:null):$sfields;
		$keyword = empty($keyword) ? (isset($_REQUEST['_keyword'])?  $_REQUEST['_keyword']:null):$keyword;
		if(empty($sfields)||empty($keyword)) return null;
		$stype =  isset($_REQUEST['_stype']) ? $_REQUEST['_stype'] : $stype ;
		if(is_string($sfields)) $sfields = explode(',',$sfields);
		$tableName = $this->getTrueTableName($table);
		$fields = array_keys( $this->getTableFields( $table ) );
		$where = array('__logic'=> $logic);
		$op = isset($_REQUEST['_op']) ? $_REQUEST['_op'] : $op ;
		
		//类型
		switch($stype){
			case 'left':
				$op = "%".$op;
				break;
			case 'right':
				$op = $op."%";
				break;
			case 'both':
				$op = '%'.$op.'%';
				break;
			case 'none':
				$op = $op;
				break;
		}
		$op = strtoupper($op);
		
		foreach( $sfields as $key=>$field ) {
			//跳过主键过滤条件
			if( $field==$this->getPk($table)) continue;
			if( in_array( $field,$fields ) ) {
				$where[$tableName.'.'.$field.' '.$op] = trim($keyword);
 			}
		}
		return $where;
	}
	
	/**
	 * 获取单条数据记录
	 * 
	 * @param int $id
	 * @return array
	 */
	public function findItem($id){
		if($this->tableName){
			return $this->findById($id,1);
		}
		return null;
	}
	
	/**
	 * 获取真实表名
	 * 
	 * @param mixed $tabel
	 * @return string
	 */
	public function getTrueTableName($table=null){
		if($table && is_object($table)) $table = $table->tableName;
		return parent::getTrueTableName($table);
	}
	
		
	protected function _fill($data,$table=null){
		$data = parent::_fill($data,$table);
		//设置了extra_info 并且 extra_info是数组
		if(isset($data['extra_info']) && is_array($data['extra_info'])){
			$data['extra_info'] = serialize($data['extra_info']);
		}
		
		if( DS!='/' ){
			//优化图像存储路径
			$imgFields = array('avatar','logo','image','file_path');
			foreach($imgFields as $f){
				if(isset($data[$f])) $data[$f] = str_replace(DS,'/',$data[$f]);
			}
		}
		return $data;
	}
}