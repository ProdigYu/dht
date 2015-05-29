<?php

class DetailController extends BaseController{
	public function __construct(){
		parent::__construct();

		$this->infohash_model = new InfohashModel();
		$this->filelist_model = new FilelistModel();
	}

	public function get($hash){
		$infohash_filelist = $this->fetch($hash);
		$infohash = $infohash_filelist['infohash'];
		$filelist = $infohash_filelist['filelist'];

		// 404
		if (empty($infohash)){
			echo $this->app->notFound();
		}

		$view_data = [
			'name' => getattr($infohash, 'name'),
			'total_size' => pretty_size($infohash['total_size']),
			'magnet' => 'magnet:?xt=urn:btih:' . getattr($infohash, 'hash'),
			'creation_date' => getattr($infohash, 'creation_date'),
			'filelist' => $filelist,
		];
		if ($this->app->request->headers->get('X-PJAX')){
			echo $this->app->render('detail_pjax.php', $view_data);
			return;
		} else {
			echo $this->app->render('detail.php', $view_data);
		}
	}

	public function post($return=false){
		if (!$return and !$this->app->request->isAjax()){
			return false;
		}
		$hash = $this->app->request->params('hash', '');
		$infohash_filelist = $this->fetch($hash);

		if ($return){
			return $infohash_filelist;
		} else {
			header('Content-Type:application/json');
			echo json_encode($infohash_filelist);
		}
	}

	public function fetch($hash){
		$infohash = [];
		$filelist = [];
		$info = $this->infohash_model->get(['hash' => $hash]);

		if (empty($info)){
			return ['infohash' => $infohash, 'filelist' => $filelist];
		}

		$filelist = $this->filelist_model->select('*', 'infohash_id=?', [$info['id']]);
		foreach ($filelist as $k => $v) {
			$filelist[$k]['pretty_size'] = pretty_size($v['length']);
		}
		return ['infohash' => $info, 'filelist' => $filelist];
	}
}
