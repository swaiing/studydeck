<?php
class SearchIndexController extends AppController
 {
	var $name = 'SearchIndex';
	//var $uses = array('SearchIndex');
	var $searchableModels = array('Tag');

	
	function init() {
		$id = 1; // somehow I just need it to increment id of search_index table
		ini_set('max_execution_time', 120); // increase execution time
		//if($this->params['url']['check'] == 'sOmE_SeCrEt_CoDe') { // my intention is to runit just once
			$models = $this->searchableModels;
			foreach($models as $k=>$m) {
				App::import('Model',$m);
				$model = new $m();
				$data = $model->findAll();
				foreach($data as $i=>$row_data) {
					$model_data = $row_data[$model->name];
					$index = $this->indexData($model, $model_data); // indexData method is takenfrom the "searchable" behavior script as I can see no clear way to call it incontroller (maybe just didn't have enough time find it out)
					$indexForId = $model_data['id'];
					if($index) {
						$index_data = array(
							'SearchIndex' => array(
								'id' => $id,
								'model' => $model->name,
								'association_key' => $indexForId,
								'data' => $index
							)
						);
						$res = @$this->SearchIndex->save($index_data);
						$id++;

						if($res) {
							echo "Save index: $id. Model: $m. Id: $indexForId <br />";
						}
					}
				}
			}
		//}
		die(); // no need for template
	}

	// copied from "searchable" behavior
	// used in "init" method. see above.
	function indexData($model, $model_data) {
		$index = '';
		$data = $model_data;
		foreach ($data as $key => $value) {
			if (is_string($value)) {
				$columns = $model->getColumnTypes();
				print_r($columns);
				if (isset($columns[$key]) &&
in_array($columns[$key],array('longtext','mediumtext','tinytext','text','varchar','char','string')))
// I've noticed that in CakePHP 1.2 there are only 2 types of char fields: 'string'and 'text' - so I've added 'string', but haven't removed all those 'char', 'varchar'types
{
					$index = $index . ' ' . strip_tags(html_entity_decode($value,ENT_COMPAT,'UTF-8'));
				}
			}
		}
		// I need it in utf-8
		//$index = iconv('UTF-8', 'ASCII//TRANSLIT', $index);
		$index = preg_replace('/[\s]+/',' ',$index);

		return $index;
	}
}
?>