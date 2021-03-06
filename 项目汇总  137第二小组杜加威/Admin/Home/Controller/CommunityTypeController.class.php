<?php
namespace Home\Controller;
use Think\Controller;
class CommunityTypeController extends Controller {
	
	function __construct()
	{
		parent::__construct();
		session_start();
		if( !isset($_SESSION['userid']) )
		{
			//未登录或session失效，跳转首页
			Header('HTTP/1.1 303 See Other'); 
			Header('Location: /');
			return;
		}
		$userid = (int)$_SESSION['userid'];
		$data = M('user')->where('id='.$userid)->select();
		if(!$data[0]['power'])
		{
			//权限不足，跳转首页
			Header("HTTP/1.1 303 See Other"); 
			Header("Location: /");
			return;
		}
	}
    
	public function index(){
		$page = I('page');
		if( $page <= 0 )
			$page = 1;
		$count = M('community_type')->count();
		$max_page = ceil($count/10);
		$start = ($page-1)*10;
		if($start > $count)
		{
			$start = 0;
			$page = 1;
		}
		$data = M('community_type')->limit($start,10)->select();
		$this->data = $data;
		$this->count = $count;
		$this->page = $page;
		$this->maxpage = $max_page;
		$this->display();
	}


	public function add()
	{
		$name = I('name');
		$data =['type_name' => $name];
		$ret = M('community_type')->add($data);
		if($ret <= 0)
			$this->ret = 'error';
		else
			$this->ret = 'success';
		$this->display();
	}

	public function delete()
	{
		$id = (int)I('id');
		if(!$id)
		{
			$this->ret = 'error';
			$this->display();
			return;
		}
		$ret = M('community_type')->delete($id);
		if($ret <= 0)
			$this->ret = 'error';
		else
			$this->ret = 'success';
		$this->display();
	}

	public function alter()
	{
		$id = I('id');
		$data = M('community_type')->where('id='.$id)->select();	
		$this->data = $data[0];
		$this->display();
	}

	public function update()
	{
		$id = (int)I('id');
		print($id);
		$name = I('name');
		
		if(!$id | !strlen($name))
		{
			$this->ret = 'error';
			$this->display();
			return;
		}

		M('community_type')->where('id='.$id)->save(['type_name'=>$name]);
		if($ret <= 0)
			$this->ret = 'success';
		else
			$this->ret = 'error';
		$this->display();
	}

	
}
