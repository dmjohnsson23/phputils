<?php

trait Hierarchical{
  // Functions for data in a hiearchal struction (with a foriegn key to this same table)

  public $parent_id; //required database feild


  public function fetchChildren(){
    # Select all children of this item
    $query = static::prepareSQL(
      'SELECT *
      FROM $table
      WHERE parent_id = ?
      ORDER BY &orderby'
      );

    $query->execute([$this->id]);
    return $query->fetchAll();
  }




  public function fetchParent(){
    # Select this item's parent
    return static::fetchById($this->parent_id);
  }



  public function isRoot(){
    //Does the item have no parent? Then it is the root item.
    return is_null($this->parent_id);
  }





  static public function fetchRoot(){
    # Select the root item. Only one item should be root.
    $query = static::prepareSQL(
      'SELECT *
      FROM %table
      WHERE parent_id IS NULL
      LIMIT 1'
    );

    $query->execute();
    return $query->fetch();
  }
}

?>
