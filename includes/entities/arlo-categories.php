<?php

namespace Arlo\Entities;

require_once( plugin_dir_path( __FILE__ ) . '../arlo-singleton.php');

use Arlo\Singleton;

class Categories extends Singleton {
	static function get($conditions = array(), $limit = null, $import_id = null) {
		global $wpdb;

		$args = func_get_args();
				
		$cache_key = md5(serialize($args));
		$cache_category = 'ArloCategories';
		
		if($cached = wp_cache_get($cache_key, $cache_category)) {
			return $cached;
		}		
	
		$query = "SELECT c.* FROM {$wpdb->prefix}arlo_categories AS c";
		
		$where = array("import_id = " . $import_id);
	
		foreach($conditions as $key => $value) {
			// what to do?
			switch($key) {
				case 'id':
					if(is_array($value)) {
						$where[] = "c.c_arlo_id IN (" . implode(',', $value) . ")";
					} else if(!empty($value)) {
						$where[] = "c.c_arlo_id = $value";
						$limit = 1;
					}
				break;
				
				case 'slug':
					if(is_array($value)) {
						$where[] = "c.c_slug IN (" . implode(',', $value) . ")";
					} else if(!empty($value)) {
						$where[] = "c.c_slug = '$value'";
						$limit = 1;
					}
				break;
				
				case 'parent_id':
					if(is_array($value)) {
						$where[] = "c.c_parent_id IN (" . implode(',', $value) . ")";
					} else if(!empty($value)) {
						$where[] = "c.c_parent_id = $value";
					} else {
						$where[] = "c.c_parent_id = 0";
					}
					continue;
				break;
			}
		}
		
		if(!empty($where)) {
			$query .= ' WHERE ' . implode(' AND ', $where);
			$query .= ' ORDER BY c_order ASC';
		}
		
		$result = ($limit != 1) ? $wpdb->get_results($query) : $wpdb->get_row($query);
		
		wp_cache_add( $cache_key, $result, $cache_category, 30 );
		
		return $result;
	}
	
	static function getTree($start_id = 0, $depth = 1, $level = 0, $import_id = null) {
		$result = null;
		$conditions = array('parent_id' => $start_id);
		
		$categories = self::get($conditions, null, $import_id);
				
		foreach($categories as $item) {		
			$item->depth_level = $level;	
			if($depth - 1 > $level) {
				$item->children = self::getTree($item->c_arlo_id, $depth, $level+1, $import_id);
			}
			$result[] = $item;
		}
		
		return $result;
	}
}