<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * MySQLi database result.   See [Results](/database/results) for usage and examples.
 *
 * @package    Kohana/Database
 * @category   Query/Result
 * @author     Kohana Team
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class OC_Database_MySQLi_Result extends Kohana_Database_MySQLi_Result {

    /**
     * fixed function mysqli_fetch_object PHP 5.6.21 and PHP 7.0.6
     * see https://github.com/open-classifieds/openclassifieds2/issues/1864
     * @return  obj
     */
    public function current()
    {
        if ($this->_current_row !== $this->_internal_row AND ! $this->seek($this->_current_row))
            return NULL;

        // Increment internal row for optimization assuming rows are fetched in order
        $this->_internal_row++;

        if ($this->_as_object === TRUE)
        {
            // Return an stdClass
            return $this->_result->fetch_object();
        }
        elseif (is_string($this->_as_object))
        {
            // Return an object of given class name
            $ob = $this->_result->fetch_object($this->_as_object, (array) $this->_object_params);

            //mark as loaded if ORM | THIS is the nasty FIX
            if (isset(class_parents($ob)['ORM']) AND method_exists($ob,'verify_loaded'))
                $ob->verify_loaded();

            return $ob;
        }
        else
        {
            // Return an array of the row
            return $this->_result->fetch_assoc();
        }
    }

} // End Database_MySQLi_Result_Select
