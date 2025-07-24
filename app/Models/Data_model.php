<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		setlocale(LC_TIME, "ru_RU.UTF8");
	}	

	// $tale - string - name table 
	// $condition - array with filed and value for fields

	public function select($als = null)
	{

		$this->db->select($als.'.*');

	}
	public function from($table)
	{

		$this->db->from($table);

	}
	public function link()
	{

		$this->db->from('link_list links');

		return 'links';

	}
	public function like($Field,$Value)
	{

		$this->db->like($Field, $Value, 'both');

	}	
	public function where($Condition,$Value)
	{

		$this->db->where($Condition, $Value);

	}
	public function limit($int)
	{

		$this->db->limit($int);

	}	
	public function result()
	{

		$query = $this->db->get();

		$result = $query->result_array();

		return $result;

	}

	public function GetLinks($NAME_BLOCK)
	{
		$from = $this->link();

		$this->select($from);
		
		$this->where($from.'.id >',20);

		$this->limit(5);
		
		$result[$NAME_BLOCK] = $this->result();

		return $result;
	}
	public function LinkListToFile($arr)
	{
		$resq = array();

		$DIR = $_SERVER['WORKDIR'].'/load/';

		foreach ($arr as $item) {

			$resq[] = $DIR.$item['project'].'/'.$item['section'].'/'.$item['link'];

		}

		return $resq;
	}
	public function tabu($sting_audit)
		{

			$this->db->select("*");

			$this->db->from('tabu');

			$query = $this->db->get();

			$arr_res = $query->result_array();			

			foreach ($arr_res as $audit) {

				if(stristr($sting_audit, $audit['string']) != false){

					return true;

				}

			}

			return false;

		}
	public function ErrorFile()
		{

			$DIR = $_SERVER['WORKDIR'].'/load/';
			
			$ArrProjectDir = scandir($DIR);
			
			array_shift($ArrProjectDir);
			
			array_shift($ArrProjectDir);
			
			$arrFileData = array();
			
			foreach ($ArrProjectDir as $dirProject) {
			
				$ArrFile = scandir($DIR.$dirProject);
			
				foreach ($ArrFile as $itemFile) {
			
					if(stristr($itemFile, 'html')){
			
						$FSize = filesize($DIR.$dirProject.'/'.$itemFile);
			
						if($FSize < 100){
			
							$arrFileData[] = $DIR.$dirProject.'/'.$itemFile;
			
						}
			
					}
			
				}
				
			}			

			return $arrFileData;

		}
	public function GetListLoadFile()
		{

			$DIR 	= $_SERVER['WORKDIR'].'/load/';

			$arrDirPrj = scandir($DIR);

			array_shift($arrDirPrj);
			array_shift($arrDirPrj);

			$arrFileLoad = array();

			foreach ($arrDirPrj as $dirprj) {
				
				$arrFilePrj = scandir($DIR.$dirprj);

				array_shift($arrFilePrj);
				array_shift($arrFilePrj);			

				foreach ($arrFilePrj as $item_file) {

					$size = filesize($DIR.$dirprj.'/'.$item_file);

					if($size < 10){

						$arrFileLoad[] = $dirprj.'/'.$item_file;

					}

				}

			}

			return $arrFileLoad;

		}
	public function ArgGetValue(	$value = '' )
		{

			$dta_encode = $this->GetEncodeNAV();
			
			if(isset($dta_encode[$value]) && !empty($value)){
			
				return $dta_encode[$value];
			
			}else{

				return false;
			
			}

		}
	public function ArgInvertValue($cvalue = '', $svalue = '')
		{
			$_SERVER['REQUEST_URI'] = str_ireplace($cvalue,$svalue,$_SERVER['REQUEST_URI']);
		}
	public function GetEncodeNAV()
		{

			$REQUEST = $_SERVER['REQUEST_URI'];

			$REQUEST = explode('/?', $REQUEST);

			$REQUEST = array_pop($REQUEST);

			$REQUEST = explode('&', $REQUEST);

			$ArrData = array();

			foreach ($REQUEST as $key => $value) {
				
				$value = explode('=', $value);
					
						$ArrData[$value[0]] = $value[1];

			}

			return $ArrData;

		}
	public function SelectAll($table)
		{			
			
			$this->db->select("*");
			
			$this->db->from($table);
			
			if($table === 'scheduler'){
			
				$this->db->order_by('url','ASC');
			
			}else{

				$this->db->order_by('name');

			}

			
			
			$query = $this->db->get();
			
			$arr_res = $query->result_array();			
			
			return $arr_res;

		}
	public function UpdateTableLike( $table, $FieldCond, $ValueCond, $SetField, $SetValue )
		{
			
			$this->db->set($SetField, $SetValue);
			
			$this->db->like($FieldCond, $ValueCond, 'both');
			
			if($this->db->update($table)){
			
				return true;
			
			}else{
			
				return false;
			
			}

		}
	public function UpdateTable( $table, $FieldCond, $ValueCond, $SetField, $SetValue )
		{
			
			$this->db->set($SetField, $SetValue, false);
			
			$this->db->where($FieldCond, $ValueCond);
			
			if($this->db->update($table)){
			
				return true;
			
			}else{
			
				return false;
			
			}
		}		
	public function InsertProject($table, $data)
		{
			# code...
		}
	public function SelectGroupCondition( $table, $field )
		{
			
			$this->db->select('*');
			
			// $this->db->like($field, $value, 'both');
			
			$this->db->group_by($field);
			
			$q = $this->db->get($table);
			
			$result = $q->result_array();
			
			return $result;

		}
	public function SelectWhereCondition( $table, $field, $value )
		{

			$this->db->select('*');
			
			$this->db->where($field, $value);
			
			$q = $this->db->get($table);

			$result = $q->result_array();
			
			return $result;

		}
	public function SelectLikeCondition( $table, $field, $value )
		{

			$this->db->select('*');
			
			$this->db->like($field, $value, 'both');
			
			$q = $this->db->get($table);
			
			$result = $q->result_array();

			return $result;

		}
	public function DeleteDB(	$table , $id )
		{
			$this->db->delete($table, array('id' => $id));
		}
	public function deleteDuplicate($table,$field,$str)	
		{
			$arr = $this->selectDuplicate($table,$field,$str,false);

			foreach ($arr as $item) {

				if($table === 'scheduler'){
				
					$sect = 'url';
				
					$mark = $item['url'];
				
				}else{
				
					$sect = 'section';
				
					$mark = $item['link'];
				
				}
				
				$arrEss = $this->SelectFrom($table,array($field => $mark),null,null,array($sect,'DESC','id','ASC'));

				// echo '<pre>'; print_r($arrEss); echo '</pre>';

				array_shift($arrEss);

				foreach ($arrEss as $ess) {
					$ess = $this->DeleteDB($table,$ess['id']);
				}

			}
		}
	public function selectDuplicate($table,$field,$str,$group = true)
		{
			
			$this->db->select(array("c.$field", "ci.$field"));
			
			$this->db->distinct();
			
			$this->db->from("$table c");
			
			  $this->db->join("$table ci", "ci.$field = c.$field","left");
			
			  $this->db->like('ci.'.$field, $str, 'both');

			  if($group === true){
			  	
			  	$this->db->group_by("c.$field","ci.$field");

			  }
			
			  $this->db->order_by("ci.$field","ASC");
			  
			  $query =  $this->db->get();  

			  return $query->result_array();

		}
	public function SelectNotSection($limit)
		{

			$this->db->select('*');
			
			$this->db->where('section IS NULL', null, false);
			
			$this->db->or_where('section', '');
			

			$this->db->limit($limit);
			
			$q = $this->db->get('link_list');
			

			$result = $q->result_array();
			
			return $result;

		}		
	public function SelectLike(	$table, $str, $action = null, $doselect = false )
		{

			$str = urldecode($str);
			
			$this->db->select('*');
			
			if($doselect == false){

				$this->db->like('link', $str, 'both');
				
				$this->db->or_like('project', $str, 'both');
				
				if($action === 'NotSection'){
				
					$this->db->group_start();
				
					$this->db->where('section IS NULL', null, false);
			
					$this->db->or_where('section', '');
			
					$this->db->group_end();
			
				}

			}
		
			// $this->db->group_start();
		
			$this->db->order_by('section', 'DESC');
		
			$this->db->order_by('link', 'ASC');
			// $this->db->group_end();
		
			$q = $this->db->get($table);
			// var_dump($q);
		
			$result = $q->result_array();
		
			return $result;
		
		}
	public function SelectLikeField( $table, $str, $field )
		{
		
			$this->db->select('*');
		
			$this->db->like($field, $str, 'both');
		
			$q = $this->db->get($table);
		
			$result = $q->result_array();
		
			return $result;
		
		}	
	public function ScanGameDirFile($arr)
		{
			$DIR 			= $_SERVER['WORKDIR'].'/game/';

			$arrDir = scandir($DIR);

			$arrDirData = array();

			foreach ($arrDir as $item) {
					
				if(strlen($item) > 5):

					if(stristr($item, 'html')):

						$arrDirData[] = $item;


					endif;

				endif;
			}


			$file = NULL;

			// echo '<pre>'; print_r($arrDirData); echo '</pre>';

			foreach ($arrDirData as $item) {

				$n = 0;

				$arrDtastri = array();

				$arritem = explode(' ', $item);

				foreach ($arritem as $elm) {
					

					if(in_array($elm, $arr)):

							$n++;
							$arrDtastri[$elm] = $n;

					endif;

					if(count($arrDtastri) > 0):

						$file = $item;
						break;

					endif;
				}

				if($file != NULL){

					break;

				}

			}

			return $file;

		}	
	public function SelectFrom( $table, $condition, $limit = null, $offset = null, $order = null)
		{
			// var_dump($condition);exit;
			
			if(!empty($table)){

				if($order != null){

					$this->db->order_by($order[0],$order[1]);

					if(isset($order[2]) && isset($order[3])){

						$this->db->order_by($order[2],$order[3]);

					}
				
				}

				if(isset($condition['like']))
				{
					foreach ($condition['like'] as $field=>$value) {
						
						$this->db->like($field, $value, 'both');

					}
				}
				
				$q = $this->db->get_where($table, $condition['where'], $limit, $offset);
				
				$result = $q->result_array();

				// echo '<pre>'; print_r($result); echo '</pre>'; exit;
				
				return $result;
			
			}else{
			
				return false;
			
			}
			
		}	

	public function InsertInto($table,$data)
		{
			if($this->db->insert($table, $data)){
				return true;
			}
			return false;
		}
	public function ReplaceInto($table,$data)
		{
			if($this->db->replace($table, $data)){
				return true;
			}
			return false;
		}


}

/* End of file Data_model.php */
/* Location: ./application/models/Data_model.php */ ?>