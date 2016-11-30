<?php

namespace IjorTengab\JsonDb;

class Directory
{
    protected $_dirname;
    protected $_files;
    protected $_loaded = [];

    public static $class = __CLASS__;

    /**
     *
     */
    public static function init($dirname)
    {
        return new static::$class($dirname);
    }

    /**
     *
     */
    public static function create($dirname)
    {
        if (!is_dir($dirname)) {
            mkdir($dirname, 0775, true);
        }
        return new static::$class($dirname);
    }

    /**
     *
     */
    public function createFile($filename = null)
    {
        $id = uniqid();
        if ($filename === null) {
            $filename = $id;
        }
        $path = $this->_dirname . '/' . $filename;
        // Jika belum ada extention json, maka tambahkan.
        if (substr($path, -5) != '.json') {
            $path .= '.json';
        }
        $attributes['_id'] = $id;
        $attributes['_created'] = microtime(true);
        return new File($path, $attributes);
    }

    /**
     *
     */
    public function __construct($dirname = null)
    {
        if ($dirname === null) {
            $dirname = getcwd();
        }
        $this->_dirname = $dirname;
        if (is_readable($dirname)) {
            $files = scandir($dirname);
            $this->_files = array_map(function ($value) {
                return substr($value, 0, -5);
            }, array_filter($files, function ($value) {
                switch ($value) {
                    case '.':
                    case '..':
                        return false;
                }
                if (substr($value, -5) == '.json') {
                    return true;
                }
            }));
        }
    }

    /**
     *
     */
    public function __get($value)
    {
        // $debugname = 'value'; echo "\r\n<pre>" . __FILE__ . ":" . __LINE__ . "\r\n". 'var_dump(' . $debugname . '): '; var_dump($$debugname); echo "</pre>\r\n";

        if ($this->hasFile($value)) {
            if (array_key_exists($value, $this->_loaded)) {
                return  $this->_loaded[$value];
            }
            else {
                $path = $this->_dirname . '/' . $value . '.json';
                $this->_loaded[$value] = ModelJson::load($path);
                return  $this->_loaded[$value];
            }
        }
        else {
            if (array_key_exists($value, $this->_loaded)) {
                return  $this->_loaded[$value];
            }
            else {
                $this->_loaded[$value] = new ModelJson(null, []);
                return  $this->_loaded[$value];
            }
        }
    }

    /**
     *
     */
    public function hasFile($file)
    {
        return in_array($file, $this->_files);
    }

    /**
     *
     */
    public function count()
    {
        return count($this->_files);
    }

    /**
     *
     */
    public function getFiles()
    {
        return $this->_files;
    }
}
