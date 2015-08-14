<?php

namespace ArloAPI\Resources;

// load main Transport class for extending
require_once 'Resource.php';

// now use it
use ArloAPI\Resources\Resource;

class EventSearch extends Resource
{
	protected $apiPath = '/resources/eventsearch';

	public function search($template_id = null, $fields = array(), $count = 20, $group_by = null, $skip=0) {
		$templates = $template_id;
		
		if(is_array($templates)) {
			$templates = '[' . implode(',', $templates) . ']';
		}
		
		$data = array(
			'fields=' . implode(',', $fields),
			'top=' . $count,
			'includeTotalCount=true',
			'skip=' . $skip,
			'format=json'
		);
		
		if(!is_null($templates)) {
			$data[] = 'filter=templateid=' . $templates;
		}
		
		$results = $this->request(implode('&', $data));
		
		$ordered = [];
		
		if($group_by && in_array($group_by, $fields)) {
		
			foreach($results->Items as $result) {
				$key = $result->{$group_by};
			
				if(!isset($output[$key])) $output[$key] = [];
			
				$ordered[$key][] = $result;
			}
			
			$results->Items = $ordered;
		}
		
		return $results;
	}
	
	/* Helper functions */
	
	/**
	 * getEvent
	 *
	 * A wrapper for search() providing an easy way to return all events
	 *
	 * @param array $fields
	 *
	 * @return array
	 */
	/*public function getAllEvents($fields=array()) {
		return $this->search(null, $fields, 1000, null);
	}*/
	
	/**
	 * getAllEvents
	 *
	 * A wrapper for search() providing an easy way to return all events
	 *
	 * @param array $fields
	 *
	 * @return array
	 */
	public function getAllEvents($fields=array()) {
		$maxCount = 200;
	
		$result = $this->search(null, $fields, $maxCount);
		
		$items = $result->Items;
		
		// get items over and above the max 200 imposed by the API
		// Dirty... but is a limitation of the public API
		if($result->TotalCount > $maxCount) {
			$iterate = ceil($result->TotalCount/$maxCount)-1;// we've already gone once - minus 1
			
			for($i=1;$i<=$iterate;$i++) {
				$items = array_merge($items, $this->search(null, $fields, $maxCount, null, $i*$maxCount)->Items);
			}
		}
		
		return $items;
	}
	
	/**
	 * getAllEventsByTemplateIDs
	 *
	 * A wrapper for search() providing an easy way to return all events by template IDs
	 *
	 * @param array $ids
	 * @param array $fields
	 *
	 * @return array
	 */
	public function getAllEventsByTemplateIDs($ids, $fields=array()) {
		return $this->search($ids, $fields, 1000, null)->Items;
	}
}