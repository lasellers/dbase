<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace org\majkel\dbase;

use Iterator;
use Countable;
use ArrayAccess;
use DateTime;

/**
 * Stores header information
 *
 * @author majkel
 */
class Header implements IHeader, Iterator, Countable, ArrayAccess {

    use Flags;

    const FLAG_VALID = 1;
    const FLAG_TRANSACTION = 2;

    /** @var Field[] */
    protected $fields = [];
    /** @var integer; */
    protected $version;
    /** @var \DateTime; */
    protected $lastUpdate;
    /** @var integer */
    protected $recordsCount;
    /** @var integer */
    protected $recordSize;
    /** @var integer */
    protected $headerSize;

    /**
     * {@inheritdoc}
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * @param \org\majkel\dbase\Field $field
     * @return \org\majkel\dbase\Header
     */
    public function addField(Field $field) {
        $this->fields[] = $field;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldsNames() {
        $fieldsNames = [];
        foreach ($this->getFields() as $field) {
            $fieldsNames[] = $field->getName();
        }
        return $fieldsNames;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion() {
        return $this->version;
    }

    /**
     * @param integer $version
     * @return \org\majkel\dbase\Header
     */
    public function setVersion($version) {
        $this->version = (integer)$version;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastUpdate() {
        return $this->lastUpdate;
    }

    /**
     * @param \DateTime $lastUpdateDate
     * @return \org\majkel\dbase\Header
     */
    public function setLastUpdate(DateTime $lastUpdateDate) {
        $this->lastUpdate = $lastUpdateDate;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldsCount() {
        return count($this->getFields());
    }

    /**
     * {@inheritdoc}
     */
    public function isPendingTransaction() {
        return $this->flagEnabled(self::FLAG_TRANSACTION);
    }

    /**
     * @param boolean $isPendingTransaction
     * @return \org\majkel\dbase\Header
     */
    public function setPendingTransaction($isPendingTransaction) {
        return $this->enableFlag(self::FLAG_TRANSACTION, $isPendingTransaction);
    }

    /**
     * {@inheritdoc}
     */
    public function getRecordsCount() {
        return $this->recordsCount;
    }

    /**
     * @param integer $recordsCount
     * @return \org\majkel\dbase\Header
     */
    public function setRecordsCount($recordsCount) {
        $this->recordsCount = (integer)$recordsCount;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRecordSize() {
        return $this->recordSize;
    }

    /**
     * @param integer $recordSize
     * @return \org\majkel\dbase\Header
     */
    public function setRecordSize($recordSize) {
        $this->recordSize = (integer)$recordSize;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderSize() {
        return $this->headerSize;
    }

    /**
     * @param integer $headerSize
     * @return \org\majkel\dbase\Header
     */
    public function setHeaderSize($headerSize) {
        $this->headerSize = (integer)$headerSize;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isValid() {
        return $this->flagEnabled(self::FLAG_VALID);
    }

    /**
     * @param boolean $valid
     * @return \org\majkel\dbase\Header
     */
    public function setValid($valid) {
        return $this->enableFlag(self::FLAG_VALID, $valid);
    }

    /**
     * @return integer
     */
    public function count() {
        return $this->getFieldsCount();
    }

    /**
     * @return org\majkel\dbase\Field
     */
    public function current() {
        return current($this->fields);
    }

    /**
     * @return string
     */
    public function key() {
        return key($this->fields);
    }

    /**
     * @return void
     */
    public function next() {
        next($this->fields);
    }

    /**
     * @return void
     */
    public function rewind() {
        reset($this->fields);
    }

    /**
     * @return boolean
     */
    public function valid() {
        return $this->key() !== null;
    }

    /**
     * @param integer $offset
     * @return boolean
     */
    public function offsetExists($offset) {
        return isset($this->fields[$offset]);
    }

    /**
     * @param integer $offset
     * @return org\majkel\dbase\Field
     */
    public function offsetGet($offset) {
        return $this->fields[$offset];
    }

    /**
     * @param integer $offset
     * @param \org\majkel\dbase\Field $value
     * @throws Exception
     */
    public function offsetSet($offset, $value) {
        if (!$value instanceof Field) {
            throw new Exception("Header can contain only Field elements");
        }
        $this->fields[$offset] = $value;
    }

    /**
     * @param integer $offset
     */
    public function offsetUnset($offset) {
        unset($this->fields[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function getField($indexOrName) {
        if ($this->offsetExists($indexOrName)) {
            return $this->offsetGet($indexOrName);
        }
        foreach ($this->fields as $field) {
            if ($field->getName() === $indexOrName) {
                return $field;
            }
        }
        throw new Exception("Field `$indexOrName` does not exists");
    }

}
