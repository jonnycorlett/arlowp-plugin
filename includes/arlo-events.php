<?php

namespace Arlo;

require_once 'arlo-singleton.php';

use Arlo\Singleton;

class Events extends Singleton {
	static function get($conditions=array(), $order=array(), $limit=null) {
		global $wpdb;
	
		$query = "SELECT e.* FROM {$wpdb->prefix}arlo_events AS e";
		
		$where = array();
	
		// conditions
		foreach($conditions as $key => $value) {
			// what to do?
			switch($key) {
				case 'id':
					if(is_array($value)) {
						$where[] = "e.e_arlo_id IN (" . implode(',', $value) . ")";
					} else {
						$where[] = "e.e_arlo_id = $value";
						$limit = 1;
					}
				break;
				
				case 'template_id':
					if(is_array($value)) {
						$where[] = "e.et_arlo_id IN (" . implode(',', $value) . ")";
					} else {
						$where[] = "e.et_arlo_id = $value";
					}
				break;
			}
		}
		
		// where
		if(!empty($where)) {
			$where = ' WHERE ' . implode(' AND ', $where);
		}
		
		// order
		if(!empty($order)) {
			$order = ' ORDER BY ' . implode(', ', $order);
		}
		
		$result = ($limit != 1) ? $wpdb->get_results($query.$where.$order) : $wpdb->get_row($query.$where.$order);
		
		return $result;
	}
}