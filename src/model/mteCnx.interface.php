<?php
/**
 * Log management interface
 *
 * @filesource
 * @package    motte
 * @subpackage model
 * @version    1.0
 * @license    http://opensource.org/licenses/gpl-license.php GPL - GNU Public license
 * @author     Pedro Gauna (pgauna@gmail.com) /
 *             Carlos Gagliardi (carlosgag@gmail.com) /
 *             Braulio Rios (braulioriosf@gmail.com) /
 *             Pablo Erartes (pabloeuy@gmail.com) /
 *             GBoksar/Perro (gustavo@boksar.info)
 * @link       http://motte.codigolibre.net Motte Website
 */
interface mteCnxInterface {

	/**
         * connect()
	 *
	 * @access public
	 */
	public function connect();

	/**
         * disconnect()
	 *
	 * @access public
	 */
	public function disconnect();
	/**
         * showTables()
	 *
	 * @access public
	 */
	public function showTables();
	/**
         * describeTable()
	 *
	 * @access public
	 */
	public function describeTable();
	/**
         * getFieldsName()
	 *
	 * @access public
	 */
	public function getFieldsName();
	/**
         * getFieldsKeyName()
	 *
	 * @access public
	 */
	public function getFieldsKeyName();
	/**
	 * executeSql()
	 *
	 * @access public
	 */
	public function executeSql();
	/**
         * getConcat()
         * receives an array of strings
	 *
	 * @access public
	 * @param array $strings
	 * @return string
	 */
	public function getConcat();
	/**
         * executeSqlLimit()
	 * returns "limit" order for the SQL sentence
	 *
	 * @access public
	 */
	public function executeSqlLimit();
	
	/**
	 * Gets a resource result from the given query
	 * @return resource
	 */
	public function getSqlResource();
	
	/**
	 * Gets a resource result from the given query, limited by the given offset and limit
	 * @return resource
	 */
	public function getSqlResourceLimit();
	
	/**
	 * fetch one row from a resource given, and optionally a given row
	 * @return array
	 */
	static function fetchRow($query);
	
	/**
	 * get the number of rows fetched in the given resource
	 * @return integer
	 */
	static function getRecordCount($query);
}
?>