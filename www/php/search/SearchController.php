<?php

class SearchController extends BaseController{
	public function __construct(){
		parent::__construct();

		$this->infohash_model = new InfohashModel();

		$sphinx = new SphinxClient();
		$sphinx->SetServer(Config::get('app.sphinx.host'), Config::get('app.sphinx.port'));
		$sphinx->SetArrayResult(true);
		$this->sphinx = $sphinx;
	}

	public function get(){
		if (!$this->app->request->params('q')){
			$this->app->redirect('/');
		}
		$view_data = $this->post(true);
		echo $this->app->render('search.php', $view_data);
	}

	public function post($return=false){
		if (!$return and !$this->app->request->isAjax()){
			return false;
		}

		$query = trim($this->app->request->params('q', ''));
		$page = (int)$this->app->request->params('p', 1);
		if ($page <= 0){
			$page = 1;
		}
		$limit = 30;

		$fetch_search = $this->fetch($query, $page, $limit);

		$view_data['q'] = $query;
		$view_data['p'] = $page;
		$view_data['total'] = $total = $fetch_search['sphinx']['total'];

		if ($total <= 0){
			$view_data['result'] = [];
			$view_data['has_more'] = false;
		} else {
			$view_data['result'] = $fetch_search['db'];
			// 匹配到的关键字生成样式
			if (!empty($view_data['result'])){
				$keywords = explode(' ', $query);
				$pattern = '#';
				foreach ($keywords as $k2 => $v2) {
					$pattern .= '('.$v2.')|';
				}
				$pattern = rtrim($pattern, '|') . '#i';
				foreach ($view_data['result'] as $k => $v) {
					$view_data['result'][$k]['name'] = preg_replace($pattern, '<span class="keyword">\\0</span>', $v['name']);

					$view_data['result'][$k]['magnet'] = 'magnet:?xt=urn:btih:' . strtoupper($v['hash']);

					$view_data['result'][$k]['pretty_size'] = pretty_size(getattr($v, 'total_size', 0));
				}
			}
			$view_data['has_more'] = $total > $limit*$page;
		}

		if ($return){
			return $view_data;
		} else {
			header('Content-Type: application/json');
			$view_data['code'] = 0;
			echo json_encode($view_data);
		}
	}

	public function fetch($query, $page, $limit){
		$sphinx_result = [];
		$db_result = [];

		if ($page <= 0){
			$page = 1;
		}

		$this->sphinx->SetLimits($limit*($page-1), $limit);
		$sphinx_query = str_replace(' ',',',trim($query));
		$sphinx_result = $this->sphinx->Query($sphinx_query);
		if (!empty($sphinx_result['matches'])){
			foreach ($sphinx_result['matches'] as $k => $match) {
				$id = $match['id'];
				$db_result[] = $this->infohash_model->get(['id' => $id]);
			}
		}
		return ['sphinx' => $sphinx_result, 'db' => $db_result];
	}

}
