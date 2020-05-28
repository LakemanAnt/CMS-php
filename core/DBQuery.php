<?php

namespace core;

class DBQuery
{
    protected $type;
    protected $fields;
    protected $where;
    protected $tableName;
    protected $isertingRow;
    protected $updatingRow;
    public function __construct($tableName)
    {
        $this->tableName = $tableName;
        $this->type = null;
        $this->fields = '*';
        $this->where = [];
    }

    public function insert($row)
    {
        $this->type = 'INSERT';
        $this->isertingRow = $row;
    }

    public function select($fields = '*')
    {
        $this->type = 'SELECT';
        $this->fields = $fields;
        return $this;
    }
    public function delete()
    {
        $this->type = 'DELETE';
        return $this;
    }
    public function update($row)
    {
        $this->type = 'UPDATE';
        $this->updatingRow = $row;
        return $this;
    }
    protected function generateWherePart($where)
    {
        $fieldsList = array_keys($where);
        $valuesList = array_values($where);
        $whereComponents = [];
        foreach ($fieldsList as $item) {
            array_push($whereComponents, "{$item} = :{$item}");
        }
        $wherePart = implode(' AND ', $whereComponents);
        return $wherePart;
    }
    public function where($condition)
    {
        if (is_string($condition))
            array_push($this->where, $condition);
        if (is_array($condition))
            $this->where = array_merge($this->where, $condition);
        return $this;
    }
    protected function generateParamsArray($row)
    {
        $params = [];
        foreach ($row as $key => $item) {
            $params[':' . $key] = $item;
        }
        return $params;
    }
    public function getSQL()
    {
        switch ($this->type) {
            case 'UPDATE':
                $wherePart = $this->generateWherePart($this->where);
                $setPartArray = [];
                $params = [];
                foreach ($this->updatingRow as $key => $value) {
                    array_push($setPartArray, $key . ' = :' . $key);
                    $params[':' . $key] = $value;
                }
                foreach ($this->where as $key => $value) {
                    $params[':' . $key] = $value;
                }
                $setPartString = implode(', ', $setPartArray);
                $sql = "UPDATE {$this->tableName} SET {$setPartString} WHERE {$wherePart}";
                return ['sql' => $sql, 'params' => $params];
                break;

            case 'DELETE':
                $wherePart = $this->generateWherePart($this->where);
                $sql = "DELETE FROM {$this->tableName} WHERE {$wherePart}";
                $params = $this->generateParamsArray($this->where);
                return ['sql' => $sql, 'params' => $params];
                break;

            case 'SELECT':
                if (is_string($this->fields))
                    $fieldPart = $this->fields;
                else
                    if (is_array($this->fields))
                    $fieldPart = implode(',', $this->fields);
                else
                    return null;
                $sql = "SELECT {$fieldPart} FROM {$this->tableName}";
                if (!empty($this->where)) {
                    $wherePart = $this->generateWherePart($this->where);
                    $sql = $sql . " WHERE {$wherePart}";
                }
                $params = $this->generateParamsArray($this->where);
                return ['sql' => $sql, 'params' => $params];
                break;

            case 'INSERT':
                $fieldaList = array_keys($this->isertingRow);
                $valuesList = array_values($this->isertingRow);
                $fieldaListString = implode(', ', $fieldaList);
                $valuesParamsList = [];
                $params = [];
                foreach ($this->isertingRow as $key => $item) {
                    array_push($valuesParamsList, ':' . $key);
                    $params[':' . $key] = $item;
                }
                $valuesListString = implode(', ', $valuesParamsList);
                $sql = "INSERT INTO {$this->tableName} ({$fieldaListString}) VALUES ({$valuesListString})";
                return ['sql' => $sql, 'params' => $params];
                break;
                return null;
        }
    }
}
