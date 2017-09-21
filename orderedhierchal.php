<?php
require_once 'hierachal.php';
trait OrderedHiearchal{
  // Extends the hiearchal trait and provides some extra functions for dealing with tables where the order is significant
  use Hierarchical;

  protected function fetchAdjacentGreater(){
    # Select the item with a greater value in the orderby column that is nearest this one
    $query = static::prepareSQL(
      'SELECT *
      FROM %table
      WHERE %orderby_col > ?
      AND parent_id = ?
      ORDER BY %orderby_col
      LIMIT 1'
    );

    $query->execute([$this->{$this->orderby}, $this->parent_id]);
    return $query->fetch();
  }



  protected function fetchAdjacentLesser(){
    # Select the item with a lower value in the orderby column that is nearest this one
    $query = static::prepareSQL(
      'SELECT *
      FROM %table
      WHERE %orderby_col < ?
      AND parent_id = ?
      ORDER BY %orderby_col DESC
      LIMIT 1'
    );

    $query->execute([$this->{$this->orderby}, $this->parent_id]);
    return $query->fetch();
  }



  public function fetchNextSyling(){
    if ($this->order_asc){
      return $this->fetchAdjacentGreater();
    }
    else{
      return $this->fetchAdjacentLesser();
    }
  }



  public function fetchPrevSybling(){
    if ($this->order_asc){
      return $this->fetchAdjacentLesser();
    }
    else{
      return $this->fetchAdjacentGreater();
    }
  }



  public function fetchFirstChild(){
    $query = static::prepareSQL(
      'SELECT *
      FROM %table
      WHERE parent_id = ?
      ORDER BY %orderby
      LIMIT 1'
    );

    $query->execute([$this->parent_id]);
    return $query->fetch();
  }




  public function fetchLastChild(){
    $query = static::prepareSQL(
      'SELECT *
      FROM %table
      WHERE parent_id = ?
      ORDER BY %orderby_invrs
      LIMIT 1'
    );

    $query->execute([$this->parent_id]);
    return $query->fetch();
  }



  public function fetchNextFlat(){
    # gets the next item when iterating thei hiearchy flat

    //First attempt is to grab this items first child
    $result = $this->fetchFirstChild();
    if ($result){
      return $result;
    }

    // If there are no children, get this item's next sybling
    $result = $this->fetchNextSyling();
    if ($result){
      return $result;
    }

    // If there is no next syling get the parent's next sybling. This is our last hope so return whatever we get.
    if (!$this->isRoot()){
      return $this->fetchParent()->fetchNextSyling();
    }

    return false;
  }
}




?>
