<?php

namespace IjorTengab\JsonDb;

use IjorTengab\JsonDb\Directory;

class File
{
    protected $_filename;
    protected $_attributes;
    protected $_attributes_strange = array();
    public static $class = __CLASS__;

    /**
     *
     */
    public static function load($filename)
    {
        $array = null;
        if (is_readable($filename)) {
            $contents = file_get_contents($filename);
            $array = json_decode($contents, true);
        }
        // Jika tidak valid.
        if (null === $array) {
            $array = array();
        }
        return new static::$class($filename, $array);
    }

    /**
     * Construct.
     */
    public function __construct($filename, Array $attributes)
    {
        $this->_filename = $filename;
        $this->_attributes = $attributes;
    }

    /**
     *
     */
    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->_attributes)) {
            $this->_attributes[$name] = $value;
        }
        else {
            $this->_attributes_strange[$name] = $value;
        }
    }

    /**
     *
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        }
        if (array_key_exists($name, $this->_attributes_strange)) {
            return $this->_attributes_strange[$name];
        }
        return '';
    }

    /**
     *
     */
    public function save($save_strange = true)
    {
        $array = $this->_attributes;
        if ($save_strange) {
            $array = array_merge($this->_attributes, $this->_attributes_strange);
        }
        $contents = json_encode($array, JSON_PRETTY_PRINT);
        $result = @file_put_contents($this->_filename, $contents);
        if ($result === false) {
            return false;
        }
        return true;
    }

    /**
     *
     */
    public function getFields()
    {
        return $this->_attributes;
    }

    /**
     *
     */
    public function populate($info, $verifikator = array())
    {
        $this->_attributes = array_merge($this->_attributes, $info);
    }

    /**
     *
     */
    public function getAbsolutePath()
    {
        return $this->_filename;
    }

    /**
     *
     */
    public function symlinkTo(Directory $direktori)
    {
        $target = $this->_filename;
        $link = $direktori->getAbsolutePath().'/'.basename($target);
        return symlink($target, $link);
    }
}
