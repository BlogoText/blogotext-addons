<?php
/**
 * DS FlatDB - Dirty Script Flat Data Base
 *
 * a flat file basic data base
 * manage datas by key(id)
 *
 * This is a BETA, so, please, use it with caution and test it before use
 * in production !
 * If you want to impove, debug (...), go to the github of this project.
 * Thanks
 *
 * @package   DirtyScript
 * @author    RemRem <remrem@dirty-script.com>
 * @copyright Copyright (C) dirty-script.com,  All rights reserved.
 * @licence   MIT
 * @version   0.05.000 beta
 * @link      http://dirty-script/Data-Base
 * @link      https://github.com/DirtyScript/FlatDB
 */

/**
 * Important Patch notes
 * - 0.05.000 beta
 *   Rewrite for PSR2, this break compatibility with older version
 *     make sur to use camelCase now!
 *     IDonTLikeCamelCaseButNoChoice...
 */

namespace DirtyScript\FlatDB;

class FlatDB
{

    /**
     * current version
     */
    private $version = '0.05.000';

    /**
     * database
     */
    private $datas = array();

    /**
     * database file
     */
    private $db_file = '';

    /**
     * last error
     */
    private $last_error = '';

    /**
     * constructor
     *
     * @param string $db_file absolute path to db file
     * @param bool $auto_create_db for create db file if not exist
     */
    public function __construct($db_file, $auto_create_db = false)
    {
        $this->db_file = $db_file.'.json.gz.php';

        // create db file
        if (!$this->dbFileExists()) {
            if ($auto_create_db !== false) {
                $this->dbCreate();
            } else {
                $this->db_file = false;
                throw new \Exception('db file ' . $this->db_file .' doesn\'t exists !');
                return false;
            }
        }

        // read the db
        $this->dbRead();
    }

    /**
     * check if db file exists
     */
    public function dbFileExists()
    {
        return file_exists($this->db_file);
    }

    /**
     * create the datas base file
     *  and put data test
     *
     * @return array
     */
    public function dbCreate()
    {
        if (false === file_put_contents($this->db_file, "<?php /* <!--\n", LOCK_EX)) {
            throw new \Exception('Could not create file ' . $this->db_file);
            return false;
        }
        return true;
    }

    /**
     * rewrite database file
     *  and put data test
     *  and put all datas
     *
     * @return bool
     */
    private function dbRewrite()
    {
        $lines = '';

        foreach ($this->datas as $data_id => $datas) {
            $line = json_encode(array($data_id => $datas), JSON_FORCE_OBJECT);
            $line = substr($line, 1);
            $line = substr($line, 0, -1);
            $line = gzcompress($line, 3);
            $lines .= str_replace(array("\r","\n"), array('{{r}}', '{{n}}'), $line)."\n";
        }

        if ($this->dbCreate($this->db_file)) {
            if (false === file_put_contents($this->db_file, $lines, FILE_APPEND | LOCK_EX)) {
                throw new \Exception('Could not push data in db file ' . $this->db_file);
            } else {
                return true;
            }
        }
        $this->last_error = 'dbRewrite() fail on dbCreate()';
        return false;
    }

    /**
     * @return string the last error
     */
    public function getLastError()
    {
        return $this->last_error;
    }

    /**
     * read db file
     *
     * @return array()
     */
    public function dbRead()
    {
        $i = 0;
        $handle = fopen($this->db_file, "r");
        $lines = '';

        if (!$handle) {
            // error opening the file.
            throw new \Exception('db file read (FAIL)');
        }

        while (($line = fgets($handle)) !== false) {
            ++$i;
            // skip the first line
            if ($i !== 1) {
                $lines .= gzuncompress(str_replace(array('{{r}}', '{{n}}'), array("\r", "\n"), $line)) .',';
            }
        }

        fclose($handle);

        // avoid db read on empty file (file < 16 octets)
        if (empty($lines) && filesize($this->db_file) < 16) {
            $this->datas = array();
            return array();
        }

        $datas = json_decode('{'. trim($lines, ',') .'}', true);
        $this->datas = $datas;
        return $datas;
    }

    /**
     * reset the db
     *
     * @return bool
     */
    public function dbReset()
    {
        $this->datas = array();
        return $this->dbCreate($this->db_file);
    }

    /**
     * create a backup (simple file copy)
     * @param string $backup_name optional the name of the backup
     * if empty $backup_name add '-backup' before extension ('.json.gz.php')
     * add '.json.gz.php' to the file name
     */
    public function dbBackup($backup_name = '')
    {
        if (empty($backup_name)) {
            $backup_file = str_replace('.json.gz.php', '-backup.json.gz.php', $this->db_file);
        } else {
            $backup_file = str_replace('.json.gz.php', '-'.$backup_name .'.json.gz.php', $this->db_file);
        }
        return copy($this->db_file, $backup_file);
    }

    /**
     * get somes infos about the db
     *
     * @return array
     */
    public function dbInfos()
    {
        $infos = stat($this->db_file);
        $infos['line'] = count($this->datas);
        return $infos;
    }

    /**
     * return an export of the db
     */
    public function dbExport($format = 'json')
    {
        if ($format == 'csv') {
            $contents = '';
            $handle = fopen('php://temp', 'r+');
            foreach ($this->datas as $line) {
                fputcsv($handle, $line, ',', '"');
            }
            rewind($handle);
            while (!feof($handle)) {
                $contents .= fread($handle, 8192);
            }
            fclose($handle);
            return $contents;
        }

        if ($format == 'json') {
            return json_encode($this->datas, JSON_FORCE_OBJECT);
        }

        if ($format == 'xml') {
            $xml = new SimpleXMLElement('<DS/>');
            foreach ($this->datas as $id => $datas) {
                $e = $xml->addChild('data');
                foreach ($datas as $key => $value) {
                    $e->addChild($key, $value);
                }
            }
            return $xml->asXML();
        }

        if ($format == 'serialize') {
            return serialize($this->datas);
        }

        $this->last_error = 'unknow export format';
        return false;
    }

    /**
     * push data in db
     *
     * @param string $data_id id(key) for data ; if (is_null) auto key
     * @param multiple $data the data to store
     * @param bool $overwritedata over write data if already key exist
     * @return false or $data_id
     */
    public function dataPush($data_id, $data, $overwritedata = false)
    {
        if (empty($data_id) && $data_id !== 0 && !is_null($data_id)) {
            $this->last_error = 'data id is empty';
            return false;
        }
        if (is_null($data_id)) {
            $data_id = $this->dataNextAvailableKey();
        }

        $line = json_encode(array($data_id => $data), JSON_FORCE_OBJECT);
        $line = substr($line, 1);
        $line = substr($line, 0, -1);
        $line = gzcompress($line, 3);
        $line = str_replace(array("\r", "\n"), array('{{r}}', '{{n}}'), $line)."\n";

        // push
        if ($overwritedata === true) {
            if (!$this->dataKeyExists($data_id)) {
                $this->datas[$data_id] = $data;
                $success = file_put_contents($this->db_file, $line, FILE_APPEND | LOCK_EX);
                if (false === $success) {
                    throw new \Exception('Unable to push datas in db file, check the read/write access to your dir_db');
                }
            } else {
                $this->datas[$data_id] = $data;
                $success = $this->dbRewrite();
            }
            if ($success) {
                // return true;
                return $data_id;
            } else {
                unset($this->datas[$data_id]);
                $this->last_error = 'fail on write db file';
                return false;
            }
        } else if ($overwritedata === false) {
            if (!$this->dataKeyExists($data_id)) {
                $this->datas[$data_id] = $data;
                if (false === file_put_contents($this->db_file, $line, FILE_APPEND | LOCK_EX)) {
                    throw new \Exception('Unable to push datas in db file, check the read/write access to your dir_db');
                }
                // return true;
                return $data_id;
            } else {
                $this->last_error = 'data id alreay exists';
                return false;
            }
        }
        throw new \Exception('unknow error');
        return false;
    }


    /**
     * get datas by id
     *
     * @param string|int $data_id
     * @return array
     */
    public function dataGet($data_id)
    {
        if (!isset($this->datas[$data_id]) || !array_key_exists($data_id, $this->datas)) {
            return null;
        }
        return $this->datas[$data_id];
    }

    /**
     * return the (int)X last line of the db
     *
     * @param int $last
     * @return array
     */
    public function dataGetLastLine($last = 5)
    {
        return array_slice($this->datas, (int)-$last, $last, true);
    }

    /**
     * @param string|int $data_id
     * @retun bool
     */
    public function dataKeyExists($data_id)
    {
        return array_key_exists($data_id, $this->datas);
    }

    /**
     * @return array all keys
     */
    public function dataKeys()
    {
        return array_keys($this->datas);
    }

    /**
     * @return int the next available key
     */
    public function dataNextAvailableKey($last_key = null)
    {
        if (is_null($last_key)) {
            $last_key = $this->dataLastKey();
            if (is_null($last_key)) {
                $last_key = 0;
            }
        }

        while ($this->dataKeyExists($last_key)) {
            $last_key++;
            $last_key = $this->dataNextAvailableKey($last_key);
        }

        return $last_key;
    }

    /**
     * @return int the last key
     */
    public function dataLastKey()
    {
        end($this->datas);
        return key($this->datas);
    }

    /**
     * @return int the first key
     */
    public function dataFirstKey()
    {
        reset($this->datas);
        return key($this->datas);
    }

    /**
     * remove data by his id
     *
     * @param string|int $data_id
     * @return bool
     */
    public function dataRemove($data_id)
    {
        if (isset($this->datas[$data_id])) {
            unset($this->datas[$data_id]);
            return $this->dbRewrite();
        }
        return true;
    }

    /**
     * test a value
     *
     * to do : add support for regex:
     *
     * return @bool
     */
    private function dataTest($string, $test)
    {
        if ($test == 'is_email') {
            return filter_var($string, FILTER_VALIDATE_EMAIL);
        }
        if ($test == 'is_numeric') {
            return is_numeric($string);
        }
        if ($test == 'is_string') {
            return is_string($string);
        }
        if ($test == 'is_array') {
            return is_array($string);
        }
        if ($test == 'is_int') {
            return is_int($string);
        }
        if ($test == 'is_blank') {
            return empty($string) && !is_numeric($string);
        }
        if ($test == 'is_url') {
            if (!filter_var($string, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
                return false;
            } else {
                return true;
            }
        }
        if (strpos($test, '>=') === 0) { // 1982>=  $string
            return ($string >= substr($test, 2));
        }
        if (strpos($test, '>') === 0) { // 1982>  $string
            return (substr($test, 1) < $string);
        }
        if (strpos($test, '<=') === 0) { // 1982<=  $string
            return ($string <= substr($test, 2));
        }
        if (strpos($test, '<') === 0) { // 1982<  $string
            return (substr($test, 1) > $string);
        }
        if (strpos($test, '==') === 0) { // 1982==  $string
            return ($string == substr($test, 2));
        }
        if (strpos($test, 'strpos:') === 0) { // a tester
            return (strpos($string, substr($test, 7)) !== false);
        }
        if (strpos($test, '!strpos:') === 0) { // a tester
            return (strpos($string, substr($test, 8)) === false);
        }

        // if (strpos( $test, 'regex:' ) === 0){return substr( $string , 6 );}

        return false;
    }

    /**
     * search data
     * at this moment, only search on value when value is a string
     *
     * to do : add support for array value
     *
     * @param array $test
     *   array(
     *     array('is_int', '>10'),
     *       array('!empty')
     * @return array founded
     */
    public function dataSearch($tests, $limit = 10)
    {
        $found = array();
        $i = 0;
        foreach ($tests as $test_test) {
            foreach ($this->datas as $id => $data) {
                if (is_string($data) && $this->dataTest($data, $test_test)) {
                    $found[] = $id;
                }
            }

            ++$i;
        }

        return $found;
    }

    /**
     * increments a value, founded by his key
     * data value must be an int
     *
     * @return @mixed
     *            false , if data isn't an int
     *            int , the new value
     */
    public function dataIncrements($data_id, $create = true)
    {
        $data = $this->dataGet($data_id);
        if (!$this->dataTest($data, 'is_int')) {
            if ($create === true && $this->dataPush($data_id, 1) !== false) {
                return 1;
            }
            return false;
        }
        ++$data;
        $this->dataPush($data_id, $data, true);
        return $data;
    }

    /**
     * decrements a value, founded by his key
     * data value must be an int
     *
     * @return @mixed
     *            false , if data isn't an int
     *            int , the new value
     */
    public function dataDecrements($data_id)
    {
        $data = $this->dataGet($data_id);
        if (!$this->dataTest($data, 'is_int')) {
            return false;
        }
        if (!$this->dataTest($data, 'is_int')) {
            if ($create === true && $this->dataPush($data_id, -1) !== false) {
                return -1;
            }
            return false;
        }
        --$data;
        $this->dataPush($data_id, $data, true);
        return $data;
    }
}
