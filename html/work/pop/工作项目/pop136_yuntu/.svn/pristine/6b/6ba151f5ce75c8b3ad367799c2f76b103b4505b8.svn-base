<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'libraries/MongoDb/POP_MongoDb_Base.php';
/**
 * Cursor object, that behaves much like the MongoCursor, but permits to generating query results like CI
 */
class POP_MongoDb_Cursor extends POP_MongoDb_Base
{
	/**
	 * @var MongoCursor $_cursor the MongoCursor returned by the query
	 */
	protected $_cursor;

	/**
	 * Construct a new Mongo_Extras
	 * @param MongoCursor $cursor the cursor returned by the query
	 */
	public function __construct(MongoCursor $cursor)
	{
		$this->_cursor = $cursor;
	}

	/**
	 * Returns query results as an object
	 */
	public function result($as_object = TRUE)
	{
		$result = [];
		try {
			foreach ($this->_cursor as $doc) {
				$result[] = $as_object ? $this->_array_to_object($doc) : $doc;
			}
		} catch (Exception  $exception) {
			return $this->_handle_exception($exception->getMessage(), $as_object);
		}
		return $result;

	}

	/**
	 * Returns query results as an array
	 */
	public function result_array()
	{
		return $this->result(FALSE);
	}

	/**
	 * Returns query results as an object
	 */
	public function result_object()
	{
		return $this->result();

	}

	/**
	 * Returns the number of the documents fetched
	 */
	public function num_rows()
	{
		return $this->count(TRUE);
	}

	/**
	 * Returns the document at the specified index as an object
	 */
	public function row($index = 0, $as_object = TRUE)
	{
		$size = $this->_cursor->count();
		$this->_cursor->reset();
		$res = [];
		for ($i = 0; $i < $size; $i++) {
			$this->_cursor->next();
			if ($i == $index && $index <= $size) {
				$res = $as_object ? (object)$this->_cursor->current() : $this->_cursor->current();
				break;
			}
		}
		return $res;
	}

	/**
	 * Returns the document at the specified index as an array
	 */
	public function row_array($index = 0)
	{
		return $this->row($index, NULL, FALSE);
	}

	/**
	 * Skip the specified number of documents
	 */
	public function skip($x = FALSE)
	{
		if ($x !== FALSE && is_numeric($x) && $x >= 1) {
			return $this->_cursor->skip((int)$x);
		}
		return $this->_cursor;
	}


	/**
	 * Limit results to the specified number
	 */
	public function limit($x = FALSE)
	{
		if ($x !== FALSE && is_numeric($x) && $x >= 1) {
			return $this->_cursor->limit((int)$x);
		}
		return $this->_cursor;
	}

	/**
	 * Sort by the field
	 */
	public function sort($fields)
	{
		return $this->_cursor->sort($fields);
	}

	/**
	 * Count the results
	 */
	public function count($foundOnly = FALSE)
	{
		$count = [];
		try {
			$count = $this->_cursor->count($foundOnly);
		} catch (MongoCursorException $exception) {
			show_error($exception->getMessage(), 500);
		} catch (MongoConnectionException $exception) {
			show_error($exception->getMessage(), 500);
		} catch (MongoCursorTimeoutException $exception) {
			show_error($exception->getMessage(), 500);
		}
		return $count;
	}

	/**
	 * Private method to convert an array into an object
	 */
	private function _array_to_object($array)
	{
		if (!is_array($array)) {
			return $array;
		}

		$object = new stdClass();
		if (is_array($array) && count($array)) {
			foreach ($array as $name => $value) {
				$name = strtolower(trim($name));
				if (!empty($name)) {
					$object->$name = $value;
				}
			}
			return $object;
		} else {
			return FALSE;
		}
	}
}
