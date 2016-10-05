<?php
/**
 * 应用控制器
 * appcontroller.php
 *
 * @copyright Copyright (c) 2010-2014 Itbeing (Beijing) Tech co.,Ltd
 * @author itbeing<td@itbeing.com>
 * @package iprank.co
 * @version 2014-05-08
 */
class AppController extends Controller{

	protected $beforeFilter = array('loadUser');
	
	protected $endFilter = array('debug');
	
	protected $user = array();
	
	function __construct($app){
		helper( array( 'app','application','system','model','array','url','datetime', 
			'datetime_ex','string_ex', 'array_ex', 'network','me', 'resources', 'models') );
		parent::__construct($app);
	}
	
	/**
	 * 初始化
	 */
	public function init(){
		$this->loadSysConfig();
		//$this->loadFilterConfig();
	}
	
	/**
	 * 加载前置过滤配置
	 */
	function loadFilterConfig(){
		if(file_exists(APP_PATH.DS.'config'.DS.'before_filter.php')){
			$filters  = include(APP_PATH.DS.'config'.DS.'before_filter.php');
			if(isset($filters[$this->name])){
				$filter = $filters[$this->name];
				$this->beforeFilter = array_merge($this->beforeFilter,$filter);
			}
		}
	}
	
	/**
	 * 加载验证器
	 * 
	 * @param string $name
	 * @param object $callback
	 * @return Validation
	 */
	public function loadValidation($name,$callback=null){
		$className = 'Corex_Validation_'.$name;
		if(import('Corex/Validation/'.$name)){
			$callback = $callback ? $callback : $this;
			return new $className($callback);
		}
		return false;
	}
	
	/**
	 * 加载系统配置
	 */
	public function loadSysConfig(){
		//如果系统配置临时文件不存在，或者系统处理debug状态，重新从数据库加载配置
		$expire = false;
		$configs = array();
		//如果在产品环境下 自动关闭调试日志
		if(c('app_model')=='product'){
			c('loger_setting',false);
			$expire = 3600*24*365;
			$configs = cache('__system_setting');
		}
		if(empty($configs)){
			$configs = array();
			$iSysConfig = new Itbeing_SysConfig();
			$configures = $iSysConfig->getConfigs(false,1);
			foreach($configures as $item){
				if($item['conf_value']!='') {
					$configs[$item['code']] = $item['conf_value'];
				}
			}
		}
		if($configs){
			if($expire) cache('__system_setting',$configs,$expire);
			c($configs);
		}
	}
	
	/**
	 * 加载用户数据
	 */
	function loadUser(){
		$userId = intval( Session::get('user_id') );
		
		//没有登陆，并且设置了自动登陆cookie
		if(!$userId){
			$sid = $this->app->getRequest('__login_sid',Cookie::get('__login_sid'));
			if($sid){
				helper('decode');
				$userId = intval(sp_decrypt_str($sid,c('secret_key')));
				Session::set('user_id',$userId);
			}
		}
		
		if( $userId ){
			$user = m('User')->loadUser( intval($userId) );
			c('__user',$user);
			$this->user = $user;
		}
	}
	
	/**
	 * 获取分页对象类
	 * 
	 * @param Model $model
	 * @param array $cond
	 * @return object $pager
	 */
	protected function getPager($model,$cond=array()){
		//时间
		$where = isset($cond['where']) ? $cond['where'] : array();
		$timefields = $this->app->getRequest('__tfield','created_at');
		$timefields = is_array($timefields) ? $timefields : explode(',',$timefields); 
		$startAt = $this->app->getRequest('__start_at',0);
		$endAt = $this->app->getRequest('__end_at',0);
		helper('datetime');
		$tn = $model;
		//处理某段时间的数据检索，必须设置起始时间
		$startAt = $startAt ? get_timestamp($startAt) : 0;
		$endAt = $endAt ? get_timestamp($endAt) : ( $startAt? $startAt+24*3600 : 0 );
		if($endAt>$startAt){
			foreach($timefields as $timefiled){
				$where[$tn.'.'.$timefiled.' >='] = $startAt;
				$where[$tn.'.'.$timefiled.' <='] =$endAt;
			}
		}
		
		//处理单点时间,某一天，某一个时间点的数据，设置单一时间
		$timeAt = $this->app->getRequest('__time_at');
		if($timeAt){
			$timeAt = is_array($timeAt) ? $timeAt : array($tn.'.created_at'=>$timeAt);
			foreach($timeAt as $f=>$v){
				if($f && $v){
					if(intval($v)==$v){ //按照天数
						$s = time();
						$e = $s-(intval($v))*3600*24;
					}else{	//设置时间
						$s = get_timestamp($v);
						$e = $s+24*3600;
					}
					$where[$tn.'.'."{$f} >="] = $s;
					$where[$tn.'.'."{$f} <="] =  $e;
				}
			}
		}
		$cond['where'] = $where;
		$pager = new Corex_ModelPager( Itbeing::loadModel($model),$cond );
		$pager->exec();
		return $pager;
	}
	
	/**
	 * 模型操作
	 * 
	 * @param mixed $model	模型名或者模型对象
	 * @param string $act
	 * @param string $feild
	 * @param mixed $value int or array
	 * @return mixed 成功返回id数组，失败返回false
	 */
	protected function _action($model,$act,$feild='id',$value=0){
		$value = $value ? $value : $this->app->getRequest($feild);
		if(!is_array($value)){
			//$value = $feild=='id' ? intval( $value ) : $value;
			$value = array( $value );
		}
		$data = array();
		
		switch($act){
			case 'trash':	//删除
			case 'remove':
				if( Itbeing::loadModel( $model )->removeByCond( array( $feild.' IN' => $value) ) ) return $value;
				return false;
				break;
			case 'on':	//上架或者生效
			case 'off':	//下架或者失效
				$data['status'] = $act=='on' ? 1 : 0;
				break;
			case 'new':		//新品
			case 'unnew': //取消新品
				$data['is_new'] = $act=='new' ? 1 : 0;
				break;
			case 'hot':	//热销
			case 'unhot':	//取消热销
				$data['is_hot'] = $act=='hot' ? 1 : 0;
				break;
			case 'best':	//精品推荐
			case 'unbest':	//取消精品
				$data['is_best'] = $act=='best' ? 1 : 0;
				break;
			case 'recommend':	//推荐
			case 'unrecommend':	//取消推荐
				$data['is_recommend'] = $act=='recommend' ? 1 : 0;
				break;
			case 'move_to':
				$toId = $this->app->getRequest('move_to_target_value',0);
				$toFeild = $this->app->getRequest('move_to_target_field','category_id');
				if(!empty($toFeild)){
					$data[$toFeild] = $toId;
				}
				break;
			case 'status':	//状态
				$status = $this->app->getRequest('target_status',0);
				$data['status'] = $status;
				break;
			default:
				if(substr($act,0,3)=='on_'){	//支持on_sale,off_sale
					$data["is_".substr($act,3)] = 1;
				}elseif(substr($act,0,4)=='off_'){
					$data["is_".substr($act,4)] = 0;
				}
				break;
		}
		if( !empty( $value ) && !empty($data) && Itbeing::loadModel( $model )->updateByCond( array( $feild.' IN' => $value) ,$data) ){
			return $value;
		}
		return false;
	}
	
	/**
	 * 覆盖支持restfull重载方法
	 * 
	 * @param string $name
	 * @return string
	 */
	public function getActionName($name=null) {
		if($name==null) $name = $this->action;
		$format = $this->app->format;
		if($format!='html' && method_exists($this,"__{$name}_{$format}")) return "__{$name}_{$format}";
		return $name;
	}
	
	/**
	 * 调试打印数据
	 * 
	 * @param mixed $data;
	 */
	function debug($data){
		if($this->app->getRequest('__debug') && c('app_model')!='product' ){
			print_r($_REQUEST);
			print_r($_SESSION);
			print_r($_COOKIE);
			echo "system configure:\n====";
			print_r(c());
			print_r($this);
			echo "system log:\n====";
			$loger = Loger::log(false,1);
			echo $loger->log;
		}
	}
	
	/**
	 * 删除
	 */
	public function remove($tableName){
		$value = $this->app->getRequest('id');
		$msg = __('app.msg_act_failed'); $redirect = null;
		if($this->_action($tableName,'remove',$feild='id',$value)){
			$msg = __('app.msg_act_done');
			$redirect = get_redirect_url( url('c='.$this->name ));
		}
		return $this->msg($msg, $redirect);
	}
	
	/**
	 * 保存数据
	 * 
	 * @param array $table
	 * @param array $data
	 * @param string $redirect
	 */
	public function save($table,$data=null,$redirect=null) {
		$data = $data ? $data : $this->app->getRequest('item');
		$msg = __('app.msg_act_failed');
		//验证表单
		if ($validation = $this->loadValidation(str_replace('_','',$table))) {
			if (!$validation->check($data)) {
				return $this->msg($validation->getErrorString());
			}
			$data = $validation->data;
		}
		$model = _m($table);
		
		//处理额外信息
		if(!empty($data['extra_info']['field'])){
			$extraInfo = array();
			$fields = $data['extra_info']['field'];
			$values = $data['extra_info']['value'];
			foreach($fields as $key=>$field){
				$value = $values[$key];
				if(!empty($value)) $extraInfo[$field] = $value;
			}
			$data['extra_info'] = $extraInfo;
		}
		
		//上传文件
		if(!empty($_FILES)){
			$iUploadFile = new Itbeing_UploadFile('logo',get_upload_allow_size(),get_upload_allow_exts());
			if($rows = $iUploadFile->doUpload()){
				$file = array_pop($rows);
				if( $data['logo'] ) $iUploadFile->doRemoveUploadFile($data['logo']);
				$data['logo'] = $file['file_path'];
			}
		}
		$this->_beforSave($data);
		$result = false;
		if($data['id']>0){
			if($data['__is_auto_fill']){
				$fields = $model->getTableFields($table);
				helper('string');
				foreach($fields as $f){
					if(starts_with($f,'has_') || starts_with($f,'is_')){	//以has_,is_开头
						$data[$f] = isset($data[$f])  ? $data[$f] : 0 ; 
					}
				}
			}
			$result = $model->updateById(intval($data['id']),$data);
			$data['__update'] = true;
		}else{
			$data['user_id'] = $data['user_id']?$data['user_id']:$this->user['id'];
			$data['network_id'] = $data['network_id']?$data['network_id']:$this->user['network_id'];
			$result = $data['id'] = $model->add($data);
			$data['__update'] = false;
		}
		$this->_afterSave($data);
		if($result!==false){
			$redirect = $redirect? $redirect : get_redirect_url(url('c='.$this->name));
			$msg = __('app.msg_act_done');
		}
		return $this->msg($msg,$redirect);
	}
	
	/**
	 * 获取查询条目
	 * 
	 * @param string $tableName
	 * @param string $type			all,my,relate
	 * @param array $cond
	 */
	protected function _getItems($tableName,$type='all',$cond=array()){
		return $this->getPager($tableName,$cond);
	}
	
	protected function _beforSave($data){
		return $data;
	}
	
	protected function _afterSave($data){
		return $data;
	}
}