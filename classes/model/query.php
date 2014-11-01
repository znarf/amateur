<?php namespace amateur\model;

class query
{

  public $table;

  public $type;

  public $columns;

  public $where;

  public $set;

  public $values = [];

  public $group_by;

  public $having;

  public $order_by;

  public $limit;

  public $offset;

  const select = 'select';

  const insert = 'insert';

  const update = 'update';

  const delete = 'delete';

  function __construct($table = null)
  {
    $this->table = $table;
  }

  function select($columns = null)
  {
    $this->type = self::select;
    $this->columns = $columns;
    return $this;
  }

  function insert($columns = null)
  {
    $this->type = self::insert;
    if ($columns) {
      $this->columns = $columns;
    }
    return $this;
  }

  function update()
  {
    $this->type = self::update;
    return $this;
  }

  function delete()
  {
    $this->type = self::delete;
    return $this;
  }

  function from($table)
  {
    $this->table = $table;
    return $this;
  }

  function set($set)
  {
    # sqlite doesn't support the set syntax for inserts
    if ($this->type == self::insert && db::driver() == 'sqlite') {
      $this->columns = array_keys($set);
      $this->values = [array_values($set)];
    }
    else {
      $this->set = $set;
    }
    return $this;
  }

  function values($values)
  {
    $this->values[] = $values;
    return $this;
  }

  function where($where)
  {
    $this->where = $where;
    return $this;
  }

  function and_where($where)
  {
    if (is_array($this->where)) {
      $this->where = $this->build_where($this->where);
    }
    if (is_array($where)) {
      $where = $this->build_where($where);
    }
    $this->where = $this->where . ' AND ' . $where;
    return $this;
  }

  function group_by($group_by)
  {
    $this->group_by = $group_by;
    return $this;
  }

  function having($having)
  {
    $this->having = $having;
    return $this;
  }

  function order_by($order_by)
  {
    $this->order_by = $order_by;
    return $this;
  }

  function limit($limit)
  {
    $this->limit  = $limit;
    return $this;
  }

  function offset($offset)
  {
    $this->offset = $offset;
    return $this;
  }

  function prologue()
  {
    $table = $this->build_args($this->table);
    switch ($this->type) {
      case self::select:
        $colums = empty($this->columns) ? '*' : $this->build_args($this->columns);
        return "SELECT {$colums} FROM {$table}";
      case self::insert:
        $colums = empty($this->columns) ? '' : ' (' . $this->build_args($this->columns) . ')';
        return "INSERT INTO {$table}{$colums}";
      case self::update:
        return "UPDATE {$table}";
      case self::delete:
        return "DELETE FROM {$table}";
    }
  }

  function build()
  {
    $query  = '/* Amateur Query */ ' . $this->prologue();
    if ($this->set) {
      $query .= ' SET ' . (is_array($this->set) ? $this->build_set($this->set) : $this->set);
    }
    if ($this->values) {
      $_values = array_map(function($values) { return '(' . $this->build_values($values) . ')'; }, $this->values);
      $query .= ' VALUES ' . "\n" . implode(",\n", $_values);
    }
    if ($this->where) {
      $query .= ' WHERE ' . (is_array($this->where) ? $this->build_where($this->where) : $this->where);
    }
    if ($this->group_by) {
      $query .= ' GROUP BY ' . $this->group_by;
    }
    if ($this->having) {
      $query .= ' HAVING ' . $this->having;
    }
    if ($this->order_by) {
      $query .= ' ORDER BY ' . $this->order_by;
    }
    if ($this->limit) {
      $query .= ' LIMIT ' . (int)$this->limit;
      if ($this->offset) {
        $query .= ' OFFSET ' . (int)$this->offset;
      }
    }
    return $query;
  }

  function count($where = null)
  {
    if ($where) {
      $this->where = $where;
    }
    $this->type = self::select;
    $this->columns = 'COUNT(*) as count';
    $result = $this->execute();
    $row = db::fetch_assoc($result);
    return (int)$row['count'];
  }

  function execute()
  {
    return db::execute($this->build());
  }

  function fetch_one()
  {
    $this->type = self::select;
    $result = $this->limit(1)->execute();
    return $result ? db::fetch_assoc($result) : null;
  }

  function fetch_all()
  {
    $this->type = self::select;
    $result = $this->execute();
    return $result ? db::fetch_all($result) : [];
  }

  function fetch_ids($key = 'id')
  {
    $this->type = self::select;
    $this->columns = $this->columns ?: $key;
    $result = $this->execute();
    return $result ? db::fetch_ids($result, $key) : [];
  }

  function fetch_key_values($key, $value)
  {
    $this->type = self::select;
    $result = $this->execute();
    return $result ? db::fetch_key_values($result, $key, $value) : [];
  }

  # Magic Methods

  function __toString()
  {
    return $this->build();
  }

  function __invoke()
  {
    return $this->execute();
  }

  # Static Methods

  static function quote($arg)
  {
    return $arg === (array)$arg ? self::multi_quote($arg) : self::single_quote($arg);
  }

  static function multi_quote($arg)
  {
    return array_map(['self', 'single_quote'], $arg);
  }

  static function single_quote($arg)
  {
    if ($arg === null) {
      return 'NULL';
    }
    if ($arg === (int)$arg || $arg === 'NULL' || $arg === 'NOT NULL') {
      return $arg;
    }
    return db::quote($arg);
  }

  static function build_args($args)
  {
    $args = array_map(function($arg) { return "`{$arg}`"; }, (array)$args);
    return implode(', ', $args);
  }

  static function build_where($where)
  {
    $_where = [];
    foreach ($where as $key => $value) {
      if ($value === (array)$value) {
        $value = self::multi_quote($value);
        $_where[] = $key . ' IN (' . implode(',', $value) . ')';
      } elseif ($value === 'NULL' || $value === 'NOT NULL') {
        $_where[] = $key . ' IS ' . self::single_quote($value);
      } else {
        $_where[] = $key . ' = ' . self::single_quote($value);
      }
    }
    return implode(' AND ', $_where);
  }

  static function build_set($set)
  {
    $_set = [];
    foreach ($set as $key => $value) {
      $_set[] = $key . ' = ' . self::single_quote($value);
    }
    return implode(', ', $_set);
  }

  static function build_values($values)
  {
    $_values = [];
    foreach ($values as $value) {
      $_values[] = self::single_quote($value);
    }
    return implode(', ', $_values);
  }

}
