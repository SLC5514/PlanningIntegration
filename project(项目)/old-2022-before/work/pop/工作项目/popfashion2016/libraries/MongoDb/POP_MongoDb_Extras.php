<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'libraries/MongoDb/POP_MongoDb_Base.php';
/**
 * Provide extra methods to interact with MongoDB
 */
class POP_MongoDb_Extras extends POP_MongoDb_Base
{


	/**
	 * Construct a new Mongo_Extras
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 *   Runs a MongoDB command (such as GeoNear).
	 *    See the MongoDB documentation for more usage scenarios:
	 *    http://dochub.mongodb.org/core/commands
	 * @usage : $this->cimongo->command(['geoNear'=>'buildings', 'near'=>[53.228482, -0.547847], 'num' => 10, 'nearSphere'=>true]);
	 */
	public function command($query = [])
	{
		try {
			$run = $this->db->command($query);
			return $run;
		} catch (MongoCursorException $e) {
			show_error("MongoDB command failed to execute: {$e->getMessage()}", 500);
		}
	}

	/**
	 *   Runs a MongoDB Aggregate.
	 *  See the MongoDB documentation for more usage scenarios:
	 *  http://docs.mongodb.org/manual/core/aggregation
	 * @usage : $this->cimongo->aggregate('users', [['$project' => ['_id' => 1]]]);
	 */
	public function aggregate($collection = "", $opt)
	{
		if (empty($collection)) {
			show_error("No Mongo collection selected to insert into", 500);
		}
		try {
			$c = $this->db->selectCollection($collection);
			return $c->aggregate($opt);
		} catch (MongoException $e) {
			show_error("MongoDB failed: {$e->getMessage()}", 500);
		}
	}

	/**
	 *    Ensure an index of the keys in a collection with optional parameters. To set values to descending order,
	 *    you must pass values of either -1, FALSE, 'desc', or 'DESC', else they will be
	 *    set to 1 (ASC).
	 * @usage : $this->cimongo->ensure_index($collection, ['first_name' => 'ASC', 'last_name' => -1], ['unique' => TRUE]);
	 */
	public function ensure_index($collection = "", $keys = [], $options = [])
	{
		if (empty($collection)) {
			show_error("No Mongo collection specified to add index to", 500);
		}
		if (empty($keys) || !is_array($keys)) {
			show_error("Index could not be created to MongoDB Collection because no keys were specified", 500);
		}
		foreach ($keys as $col => $val) {
			if ($val == -1 || $val === FALSE || strtolower($val) == 'desc') {
				$keys[$col] = -1;
			} else {
				$keys[$col] = 1;
			}
		}
		if ($this->db->{$collection}->ensureIndex($keys, $options) == TRUE) {
			$this->_clear();
			return $this;
		} else {
			show_error("An error occured when trying to add an index to MongoDB Collection", 500);
		}
	}


	/**
	 *    Remove an index of the keys in a collection. To set values to descending order,
	 *    you must pass values of either -1, FALSE, 'desc', or 'DESC', else they will be
	 *    set to 1 (ASC).
	 * @usage : $this->cimongo->remove_index($collection, ['first_name' => 'ASC', 'last_name' => -1]);
	 */
	public function remove_index($collection = "", $keys = [])
	{
		if (empty($collection)) {
			show_error("No Mongo collection specified to remove index from", 500);
		}
		if (empty($keys) || !is_array($keys)) {
			show_error("Index could not be removed from MongoDB Collection because no keys were specified", 500);
		}
		if ($this->db->{$collection}->deleteIndex($keys) == TRUE) {
			$this->_clear();
			return $this;
		} else {
			show_error("An error occured when trying to remove an index from MongoDB Collection", 500);
		}
	}

	/**
	 * Remove all indexes from a collection
	 */
	public function remove_all_indexes($collection = "")
	{
		if (empty($collection)) {
			show_error("No Mongo collection specified to remove all indexes from", 500);
		}
		$this->db->{$collection}->deleteIndexes();
		$this->_clear();
		return $this;
	}

	/**
	 * List all indexes in a collection
	 */
	public function list_indexes($collection = "")
	{
		if (empty($collection)) {
			show_error("No Mongo collection specified to remove all indexes from", 500);
		}
		return $this->db->{$collection}->getIndexInfo();
	}

	/**
	 * Get the documents where the value of a $field is greater than $x
	 */
	public function where_gt($field = "", $x)
	{
		$this->_where_init($field);
		$this->wheres[$field]['$gt'] = $x;
		return $this;
	}

	/**
	 * Get the documents where the value of a $field is greater than or equal to $x
	 */
	public function where_gte($field = "", $x)
	{
		$this->_where_init($field);
		$this->wheres[$field]['$gte'] = $x;
		return $this;
	}

	/**
	 * Get the documents where the value of a $field is less than $x
	 */
	public function where_lt($field = "", $x)
	{
		$this->_where_init($field);
		$this->wheres[$field]['$lt'] = $x;
		return $this;
	}

	/**
	 * Get the documents where the value of a $field is less than or equal to $x
	 */
	public function where_lte($field = "", $x)
	{
		$this->_where_init($field);
		$this->wheres[$field]['$lte'] = $x;
		return $this;
	}

	/**
	 * Get the documents where the value of a $field is between $x and $y
	 */
	public function where_between($field = "", $x, $y)
	{
		$this->_where_init($field);
		$this->wheres[$field]['$gte'] = $x;
		$this->wheres[$field]['$lte'] = $y;
		return $this;
	}

	/**
	 * Get the documents where the value of a $field is between but not equal to $x and $y
	 */
	public function where_between_ne($field = "", $x, $y)
	{
		$this->_where_init($field);
		$this->wheres[$field]['$gt'] = $x;
		$this->wheres[$field]['$lt'] = $y;
		return $this;
	}

	/**
	 * Get the documents where the value of a $field is not equal to $x
	 */
	public function where_ne($field = '', $x)
	{
		$this->_where_init($field);
		$this->wheres[$field]['$ne'] = $x;
		return $this;
	}

	/**
	 * Increments the value of a field
	 */
	public function inc($fields = [], $value = 1)
	{
		$this->_update_init('$inc');
		if (is_string($fields)) {
			$this->updates['$inc'][$fields] = $value;
		} elseif (is_array($fields)) {
			foreach ($fields as $field => $value) {
				$this->updates['$inc'][$field] = $value;
			}
		}
		return $this;
	}

	/**
	 * Decrements the value of a field
	 */
	public function dec($fields = [], $value = 0)
	{
		$this->_update_init('$dec');
		if (is_string($fields)) {
			$this->updates['$dec'][$fields] = $value;
		} elseif (is_array($fields)) {
			foreach ($fields as $field => $value) {
				$this->updates['$dec'][$field] = $value;
			}
		}
		return $this;
	}

	/**
	 * Unset the value of a field(s)
	 */

	public function unset_field($fields)
	{
		$this->_update_init('$unset');
		if (is_string($fields)) {
			$this->updates['$unset'][$fields] = 1;
		} elseif (is_array($fields)) {
			foreach ($fields as $field) {
				$this->updates['$unset'][$field] = 1;
			}
		}
		return $this;
	}

	/**
	 * Adds value to the array only if its not in the array already
	 * @usage: $this->cimongo->where(['blog_id'=>123])->addtoset('tags', 'php')->update('blog_posts');
	 * @usage: $this->cimongo->where(['blog_id'=>123])->addtoset('tags', ['php', 'codeigniter', 'mongodb'])->update('blog_posts');
	 */
	public function addtoset($field, $values)
	{
		$this->_update_init('$addToSet');
		if (is_string($values)) {
			$this->updates['$addToSet'][$field] = $values;
		} elseif (is_array($values)) {
			$this->updates['$addToSet'][$field] = ['$each' => $values];
		}
		return $this;
	}

	/**
	 * Pushes values into a field (field must be an array)
	 * @usage: $this->cimongo->where(['blog_id'=>123])->push('comments', ['text'=>'Hello world'])->update('blog_posts');
	 * @usage: $this->cimongo->where(['blog_id'=>123])->push(['comments' => ['text'=>'Hello world']], 'viewed_by' => ['Alex'])->update('blog_posts');
	 */

	public function push($fields, $value = [])
	{
		$this->_update_init('$push');
		if (is_string($fields)) {
			$this->updates['$push'][$fields] = $value;
		} elseif (is_array($fields)) {
			foreach ($fields as $field => $value) {
				$this->updates['$push'][$field] = $value;
			}
		}
		return $this;
	}

	/**
	 * Pushes  ALL values into a field (field must be an array)
	 */
	public function push_all($fields, $value = [])
	{
		$this->_update_init('$pushAll');
		if (is_string($fields)) {
			$this->updates['$pushAll'][$fields] = $value;
		} elseif (is_array($fields)) {
			foreach ($fields as $field => $value) {
				$this->updates['$pushAll'][$field] = $value;
			}
		}
		return $this;
	}

	/**
	 * Pops the last value from a field (field must be an array
	 * @usage: $this->cimongo->where(['blog_id'=>123])->pop('comments')->update('blog_posts');
	 * @usage: $this->cimongo->where(['blog_id'=>123])->pop(['comments', 'viewed_by'])->update('blog_posts');
	 */
	public function pop($field)
	{
		$this->_update_init('$pop');
		if (is_string($field)) {
			$this->updates['$pop'][$field] = -1;
		} elseif (is_array($field)) {
			foreach ($field as $pop_field) {
				$this->updates['$pop'][$pop_field] = -1;
			}
		}
		return $this;
	}

	/**
	 * Removes by an array by the value of a field
	 * @usage: $this->cimongo->pull('comments', ['comment_id'=>123])->update('blog_posts');
	 */
	public function pull($field = "", $value = [])
	{
		$this->_update_init('$pull');
		$this->updates['$pull'] = [$field => $value];
		return $this;
	}

	/**
	 * Removes ALL by an array by the value of a field
	 */
	public function pull_all($field = "", $value = [])
	{
		$this->_update_init('$pullAll');
		$this->updates['$pullAll'] = [$field => $value];
		return $this;
	}

	/**
	 * Rename a field
	 */
	public function rename_field($old, $new)
	{
		$this->_update_init('$rename');
		$this->updates['$rename'][] = [$old => $new];
		return $this;
	}
}
