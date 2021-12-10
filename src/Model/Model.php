<?php

namespace Hellokant\Model;

use Hellokant\Query\Query;

class Model
{
    protected static string $table;
    protected static string $idColumn;

    protected $_v = [];

    public function __construct(array $t = null)
    {
        if (!is_null($t)) {
            $this->_v = $t;
        }
    }

    public function __get(string $name)
    {
        if (array_key_exists($name, $this->_v)) {
            return $this->_v[$name];
        } else if (method_exists(static::class, $name)) {
            return $this->$name();
        } else {
            return null;
        }
    }

    public function __set(string $name, $val)
    {
        $this->_v[$name] = $val;
    }

    public function delete()
    {
        if (isset($this->_v[static::$idColumn])) {
            return Query::table(static::$table)
                ->where(static::$idColumn, '=', $this->_v[static::$idColumn])
                ->delete();
        } else {
            throw new \Exception("primary key is not define");
        }
    }

    public function insert()
    {
        $this->_v[static::$idColumn] = Query::table(static::$table)
            ->insert($this->_v);
        return $this->_v[static::$idColumn];
    }

    static function all()
    {
        $all = Query::table(static::$table)->get();
        $return = [];
        foreach ($all as $row) {
            $return[] = new static($row);
        }
        return $return;
    }

    static function find($id, $param = null)
    {
        if (is_int($id)) {
            if ($param != null) {
                $item = Query::table(static::$table)->select($param)->where(static::$idColumn, '=', $id)->get();
            } else {
                $item = Query::table(static::$table)->where(static::$idColumn, '=', $id)->get();
            }
        } else if (is_array($id)) {
            if ($param != null) {
                $item = Query::table(static::$table)->select($param)->where($id[0], $id[1], $id[2])->get();
            } else {
                $item = Query::table(static::$table)->where($id[0], $id[1], $id[2])->get();
            }
        }
        $return = [];
        foreach ($item as $i) {
            $return[] = new static($i);
        }
        return $return;
    }


    static function first($id, $param = null)
    {
        if (is_int($id)) {
            if ($param != null) {
                $item = Query::table(static::$table)->select($param)->where(static::$idColumn, '=', $id)->get();
            } else {
                $item = Query::table(static::$table)->where(static::$idColumn, '=', $id)->get();
            }
        } else if (is_array($id)) {
            if ($param != null) {
                $item = Query::table(static::$table)->select($param)->where($id[0], $id[1], $id[2])->get();
            } else {
                $item = Query::table(static::$table)->where($id[0], $id[1], $id[2])->get();
            }
        }
        if (isset($item[0])) {
            return new static($item[0]);
        } else {
            throw new \Exception('no result found');
        }
    }

    public function belongs_to(string $classname, string $foreignkey)
    {
        $namespace = __NAMESPACE__ . "\\$classname";
        $resolve = new $namespace();
        $res = Query::table($resolve::$table)->where($resolve::$idColumn, '=', $this->_v[$foreignkey])->get();
        return $res;
    }

    public function has_many(string $classname, string $foreignkey)
    {
        $namespace = __NAMESPACE__ . "\\$classname";
        $resolve = new $namespace();
        $res = Query::table($resolve::$table)->where($foreignkey, '=', $this->_v['id'])->get();
        $return = [];
        foreach ($res as $i) {
            $return[] = new static($i);
        }
        return $return;
    }


}