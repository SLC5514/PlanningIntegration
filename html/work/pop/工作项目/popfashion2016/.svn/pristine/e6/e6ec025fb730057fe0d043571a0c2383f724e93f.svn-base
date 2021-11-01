<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'libraries/MongoDb/POP_MongoDb_Extras.php';
require_once APPPATH . 'libraries/MongoDb/POP_MongoDb_Cursor.php';

/**
 * Provide CI active record like methods to interact with MongoDB
 */
class POP_MongoDb extends POP_MongoDb_Extras
{

	private $_inserted_id = FALSE;
	public $debug = FALSE;

	/**
	 * Construct a new Mongo
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Fake close function so you can bind $this->db=$this->cimongo
	 */
	public function close()
	{
		$this->connection->close();
	}

	public function __destruct() {
		$this->close();
	}

	/**
	 * Get the documents based upon the passed parameters
	 */
	public function get($collection = "", $limit = FALSE, $offset = FALSE)
	{
		if (empty($collection)) {
			//FIXME theow exception instead show error
			show_error("In order to retreive documents from MongoDB, a collection name must be passed", 500);
		}
		$cursor = $this->db->selectCollection($collection)->find($this->wheres, $this->selects);
		$mongo_cursor = new POP_MongoDb_Cursor($cursor);

		$this->limit = ($limit !== FALSE && is_numeric($limit)) ? $limit : $this->limit;
		if ($this->limit !== FALSE) {
			$mongo_cursor->limit($this->limit);
		}

		$this->offset = ($offset !== FALSE && is_numeric($offset)) ? $offset : $this->offset;
		if ($this->offset !== FALSE) {
			$mongo_cursor->skip($this->offset);
		}
		if (!empty($this->sorts) && count($this->sorts)) {
			$mongo_cursor->sort($this->sorts);
		}

		$this->_clear();

		return $mongo_cursor;
	}

	/**
	 * Get the documents based upon the passed parameters
	 */
	public function get_where($collection = "", $where = [], $limit = FALSE, $offset = FALSE)
	{
		return $this->where($where)->get($collection, $limit, $offset);
	}

	/**
	 * Determine which fields to include (_id is always returned)
	 */
	public function select($includes = [])
	{
		if (!is_array($includes)) {
			$includes = [];
		}
		if (!empty($includes)) {
			foreach ($includes as $col) {
				$this->selects[$col] = TRUE;
			}
		}
		return $this;
	}

	/**
	 * where clause:
	 * Passa an array of field=>value, every condition will be merged in AND statement
	 * e.g.:
	 * $this->cimongo->where(['foo'=> 'bar', 'user'=>'arny'])->get('users')
	 *
	 * if you need more complex clause you can pass an array composed exactly like mongoDB needs, followed by a boolean TRUE parameter.
	 * e.g.:
	 * 	$where_clause =[
	 * 		'$or'=>[
	 * 			['school'=>'oo'],
	 * 			['name'=>'js'],
	 * 		],
	 * 		'age'=>30,
	 * 	];
	 * $this->cimongo->where($where_clause, TRUE)->get("users")
	 */
	public function where($wheres = [], $native = FALSE)
	{
		if ($native === TRUE && is_array($wheres)) {
			$this->wheres = $wheres;
		} elseif (is_array($wheres)) {
			foreach ($wheres as $where => $value) {
				$this->_where_init($where);
				$this->wheres[$where] = $value;
			}
		}
		return $this;
	}

	/**
	 * Get the documents where the value of a $field may be something else
	 */
	public function or_where($wheres = [])
	{
		$this->_where_init('$or');
		if (is_array($wheres) && count($wheres)) {
			foreach ($wheres as $wh => $val) {
				$this->wheres['$or'][] = [$wh => $val];
			}
		}
		return $this;
	}

	/**
	 * Get the documents where the value of a $field is in a given $in [].
	 */
	public function where_in($field = "", $in = [])
	{
		$this->_where_init($field);
		$this->wheres[$field]['$in'] = $in;
		return $this;
	}

	/**
	 * Get the documents where the value of a $field is not in a given $in [].
	 */
	public function where_not_in($field = "", $in = [])
	{
		$this->_where_init($field);
		$this->wheres[$field]['$nin'] = $in;
		return $this;
	}

	/**
	 *    Get the documents where the (string) value of a $field is like a value. The defaults
	 *    allow for a case-insensitive search.
	 *
	 * @param $flags
	 *    Allows for the typical regular expression flags:
	 *        i = case insensitive
	 *        m = multiline
	 *        x = can contain comments
	 *        l = locale
	 *        s = dotall, "." matches everything, including newlines
	 *        u = match unicode
	 *
	 * @param $enable_start_wildcard
	 *    If set to anything other than TRUE, a starting line character "^" will be prepended
	 *    to the search value, representing only searching for a value at the start of
	 *    a new line.
	 *
	 * @param $enable_end_wildcard
	 *    If set to anything other than TRUE, an ending line character "$" will be appended
	 *    to the search value, representing only searching for a value at the end of
	 *    a line.
	 *
	 * @usage : $this->cimongo->like('foo', 'bar', 'im', FALSE, TRUE);
	 */
	public function like($field = "", $value = "", $flags = "i", $enable_start_wildcard = TRUE, $enable_end_wildcard = TRUE)
	{
		$field = (string)trim($field);
		$this->_where_init($field);
		$value = (string)trim($value);
		$value = quotemeta($value);

		if ($enable_start_wildcard !== TRUE) {
			$value = "^" . $value;
		}
		if ($enable_end_wildcard !== TRUE) {
			$value .= "$";
		}
		$regex = "/$value/$flags";
		$this->wheres[$field] = new MongoRegex($regex);
		return $this;
	}

	/**
	 * The same as the aboce but multiple instances are joined by OR:
	 */
	public function or_like($field, $like = [], $flags = "i")
	{
		$this->_where_init('$or');
		if (is_array($like) && count($like)) {
			foreach ($like as $admitted) {
				$this->wheres['$or'][] = [$field => new MongoRegex("/$admitted/$flags")];
			}
		} else {
			$this->wheres['$or'][] = [$field => new MongoRegex("/$like/$flags")];
		}
		return $this;
	}

	/**
	 * The same as the aboce but multiple instances are joined by NOT LIKE:
	 */
	public function not_like($field, $like = [])
	{
		$this->_where_init($field);
		if (is_array($like) && count($like)) {
			foreach ($like as $admitted) {
				$this->wheres[$field]['$nin'][] = new MongoRegex("/$admitted/");
			}
		}
		return $this;
	}

	/**
	 *    Sort the documents based on the parameters passed. To set values to descending order,
	 *    you must pass values of either -1, FALSE, 'desc', or 'DESC', else they will be
	 *    set to 1 (ASC).
	 * @usage : $this->cimongo->order_by(['name' => 'ASC'])->get('users');
	 */
	public function order_by($fields = [])
	{
		foreach ($fields as $field => $val) {
			if ($val === -1 || $val === FALSE || strtolower($val) === 'desc') {
				$this->sorts[$field] = -1;
			}
			if ($val === 1 || $val === TRUE || strtolower($val) === 'asc') {
				$this->sorts[$field] = 1;
			}
		}
		return $this;
	}

	/**
	 * Count all the documents in a collection
	 * @usage : $this->cimongo->count_all('users');
	 */
	public function count_all($collection = "")
	{
		if (empty($collection)) {
			show_error("In order to retreive a count of documents from MongoDB, a collection name must be passed", 500);
		}

		$cursor = $this->db->selectCollection($collection)->find();
		$mongo_cursor = new POP_MongoDb_Cursor($cursor);
		$count = $mongo_cursor->count(TRUE);
		$this->_clear();
		return $count;
	}

	/**
	 * Count the documents based upon the passed parameters
	 */
	public function count_all_results($collection = "")
	{
		if (empty($collection)) {
			show_error("In order to retreive a count of documents from MongoDB, a collection name must be passed", 500);
		}

		$cursor = $this->db->selectCollection($collection)->find($this->wheres);
		$mongo_cursor = new POP_MongoDb_Cursor($cursor);
		if ($this->limit !== FALSE) {
			$mongo_cursor->limit($this->limit);
		}
		if ($this->offset !== FALSE) {
			$mongo_cursor->skip($this->offset);
		}
		$this->_clear();
		return $mongo_cursor->count(TRUE);
	}

	/**
	 * Insert a new document into the passed collection
	 */
	public function insert($collection = "", $insert = [])
	{
		if (empty($collection)) {
			show_error("No Mongo collection selected to insert into", 500);
		}

		if (count($insert) == 0) {
			show_error("Nothing to insert into Mongo collection or insert is not an array", 500);
		}
		$this->_inserted_id = FALSE;
		try {
			$query = $this->db->selectCollection($collection)->insert($insert, ['w' => $this->query_safety]);
			if (isset($insert['_id'])) {
				$this->_inserted_id = $insert['_id'];
				return TRUE;
			} else {
				return FALSE;
			}
		} catch (MongoException $e) {
			show_error("Insert of data into MongoDB failed: {$e->getMessage()}", 500);
		} catch (MongoCursorException $e) {
			show_error("Insert of data into MongoDB failed: {$e->getMessage()}", 500);
		}
	}

	/**
	 * Insert a multiple new document into the passed collection
	 */
	public function insert_batch($collection = "", $insert = [])
	{
		if (empty($collection)) {
			show_error("No Mongo collection selected to insert into", 500);
		}
		if (count($insert) == 0) {
			show_error("Nothing to insert into Mongo collection or insert is not an array", 500);
		}
		try {
			$query = $this->db->selectCollection($collection)->batchInsert($insert, ['w' => $this->query_safety]);
			if (is_array($query)) {
				return $query["err"] === NULL;
			} else {
				return $query;
			}
		} catch (MongoException $e) {
			show_error("Insert of data into MongoDB failed: {$e->getMessage()}", 500);
		} catch (MongoCursorException $e) {
			show_error("Insert of data into MongoDB failed: {$e->getMessage()}", 500);
		} catch (MongoCursorTimeoutException $e) {
			show_error("Insert of data into MongoDB failed: {$e->getMessage()}", 500);
		}
	}

	/**
	 * Sets a field to a value
	 * @usage: $this->cimongo->where(['blog_id'=>123])->set(['posted'=>1])->update('users');
	 */
	public function set($fields = [])
	{
		if (is_array($fields)) {
			$this->_update_init('$set');
			foreach ($fields as $field => $value) {
				$this->updates['$set'][$field] = $value;
			}
		}
		return $this;
	}

	/**
	 * Update a single document
	 */
	public function update($collection = "", $data = [], $options = [])
	{
		if (empty($collection)) {
			show_error("No Mongo collection selected to update", 500);
		}
		if (is_array($data) && count($data)) {
			$this->_update_init('$set');
			$this->updates['$set'] += $data;
		}
		if (count($this->updates) == 0) {
			show_error("Nothing to update in Mongo collection or update is not an array", 500);
		}
		try {
			$options = array_merge(['w'=>$this->query_safety,'multiple'=>FALSE], $options);
			$this->db->selectCollection($collection)->update($this->wheres, $this->updates, $options);
			$this->_clear();
			return TRUE;
		} catch (MongoCursorException $e) {
			show_error("Update of data into MongoDB failed: {$e->getMessage()}", 500);
		} catch (MongoCursorException $e) {
			show_error("Update of data into MongoDB failed: {$e->getMessage()}", 500);
		} catch (MongoCursorTimeoutException $e) {
			show_error("Update of data into MongoDB failed: {$e->getMessage()}", 500);
		}
	}

	/**
	 * Update more than one document
	 */
	public function update_batch($collection = "", $data = [])
	{
		return $this->update($collection, $data, ['multiple' => TRUE]);
	}

	/**
	 * Delete document from the passed collection based upon certain criteria
	 */
	public function delete($collection = "", $options = [])
	{
		if (empty($collection)) {
			show_error("No Mongo collection selected to delete from", 500);
		}
		try {
			$options = array_merge(['w' => $this->query_safety], $options);
			$this->db->selectCollection($collection)->remove($this->wheres, $options);
			$this->_clear();
			return TRUE;
		} catch (MongoCursorException $e) {
			show_error("Delete of data into MongoDB failed: {$e->getMessage()}", 500);
		} catch (MongoCursorTimeoutException $e) {
			show_error("Delete of data into MongoDB failed: {$e->getMessage()}", 500);
		}
	}

	/**
	 * Delete more than one document
	 */
	public function delete_batch($collection = "")
	{
		return $this->delete($collection, ['justOne' => FALSE]);
	}

	/**
	 * Limit results
	 */
	public function limit($limit = FALSE)
	{
		if ($limit && is_numeric($limit)) {
			$this->limit = $limit;
		}
		return $this;
	}

	/**
	 * Returns the last inserted document's id
	 */
	public function insert_id()
	{
		return $this->_inserted_id;
	}
}
