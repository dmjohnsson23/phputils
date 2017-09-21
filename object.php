<?php
abstract class DBObject implements ArrayAccess{
  protected $id;

  // Always overwtie these first 3
  protected static $_pdo;
  protected static $_table; #name of the database table this object related to
  protected static $_columns; #all columns in the table, except id
  protected static $_orderby_col ='id'; // default order by columns when fetching multiple objects
  protected static $_order_asc = true; //set false to use DESC

  public function __construct($data=null){
    if (isset($this->id)) {
      # If the id is already set the object was initialized by PDO,
      # Therefore we don't need to do anything here.
      return;
    }
    if (isset($data)){
      $this->setData($data);
    }
  }



####################### Some Basic Fetch Query Functions #######################

  static public function fetchByID($id){
    # Select the proper article given id
    $query=static::prepareSQL(
        'SELECT *
        FROM %table
        WHERE id = ?'
      );

    $query->execute([$id]);
    return $query->fetch();
  }



  static public function fetchAll(){
    $query=static::prepareSQL(
        'SELECT *
        FROM %table
        ORDER BY %orderby'
      );

    $query->execute();
    return $query->fetchAll();
  }


  public function fetchAgain(){
    # Update this object to match the current value in the database
    $query=static::prepareSQL(
        'SELECT *
        FROM %table
        WHERE id = ?',
        $mode=null
      );

    $query->setFetchMode(PDO::FETCH_INTO, $this);

    $query->execute([$this->id]);
    return $query->fetch();
  }



####################### Getters and Other Utility Functions #######################
  public function getID(){
    return $this->id;
  }




  public function matches(DBObject $other){
    # Test if two db objects refor to the same row in the dataase
    return ($other::$table == static::$table and $other->id == $this->id);
  }




  ####################### Data Modification Functions #######################

  public function setData($data){
    # Given an associative array of data, such as POST data, update data as appropriate
    # This version simply sets the value verbetim from the given data without any validation or such
    # It is recommended you overwite this function in your class to behave more appropriately
    foreach (static::$_columns as $column) {
      if (isset($data[$column])){
        $this->{$column} = $data[$column];
      }
    }
  }





  public function save(){
    # Save all changes to the database
    if (isset($this->id)) {
      # If the id is already set the object has a matching row in the database already, update it

      $sql = 'UPDATE %table SET';
      $first=true;
      foreach (static::$_columns as $col) {
        if (!$first){$sql .= ', ';}
        $sql .= " $col = :$col ";
        $first=false;
      }
      $sql .= 'WHERE id = :id';

      /* Resulting string should look something like this:
        UPDATE sometable
        SET x = :x, y = :y
        WHERE id = :id
      */
    }
    else{
      # The id is not set, this is a new oject. Create it.

      $sql = 'INSERT INTO %table (';
      foreach (static::$_columns as $col) {
        $sql .= "$col, ";
      }
      $sql .= ') VALUES (';
      foreach (static::$_columns as $col) {
        $sql .= ":$col, ";
      }
      $sql .= ')';

      /* Resulting string should look something like this:
        INSERT INTO sometable
        (x, y)
        VALUES (:x, :y)
      */
    }

    # Now we should have our sql ready to go. Prepare and execute it.
    static::prepareSQL($sql, $mode=null)->execute(get_object_vars($this));
    if (!isset($this->id)){
      # Update the id of this oject, so it doesn't get created twice if this function is called again.
      $this->id = static::$_pdo->lastInsertId();
    }
  }




  ####################### Functions to Implement Array Interface #######################
  public function offsetSet($offset, $value) {
    # We don't want to try and set anything that's not a column name
    if (in_array($offset, static::$_columns)){
      $this->{$offset} = $value;
    }
  }

  public function offsetExists($offset) {
    return in_array($offset, static::$_columns) and isset($this->{$offset});
  }

  public function offsetUnset($offset) {
    # We don't want to try and unset anything that's not a column name
    # Note: if the value is unset it will not be written back to the database whan save() is called
    if (in_array($offset, static::$_columns)){
      unset($this->{$offset});
    }
  }

  public function offsetGet($offset) {
    if (in_array($offset, static::$_columns)){
      return $this->{$offset};
    }
  }


  ######################### Functions to be used internally #################
  protected static function prepareSQL($sql, $mode=PDO::FETCH_CLASS, $raw=false){
    if (!$raw){
      // some escapes to make writing sql easier

      // Get the orderby clause
      $orderby_param = static::$_orderby;
      $orderby_param_invrs = "$orderby_param DESC";
      if (!static::$order_asc){
        //Swap these vars if it's set to descending order
        $orderby_param = $orderby_param_invrs;
        $orderby_param_invrs = static::$_orderby;
      }

      // Replace some strings that represent class values
      strtr($sql,
        [
          '%table' => static::$_table,
          '%orderby_col' => static::$_orderby,
          '%orderby' => $orderby_param,
          '%orderby_invrs' => $orderby_param_invrs,
        ]
      );
    }


    // Prepare the query
    $query = static::$pdo->prepare($sql);

    // Set the appropriate fetch mode. By default is should fetch into an instance of this class.
    switch ($mode) {
      case PDO::FETCH_CLASS: // Fetch an instance of this class
        $query->setFetchMode($mode, get_called_class());
        break;

      case PDO::FETCH_COLUMN: // Fetch a single value
        $query->setFetchMode($mode, 0);
        break;

      case null: // Don't set the fetch mode here
        break;

      default:
        $query->setFetchMode($mode);
        break;
    }
    return $query;
  }
}
?>
