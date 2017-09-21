<?php
trait Ordered{
  // provides some extra functions for dealing with tables where the order is significant


  protected function fetchAdjacentGreater(){
    # Select the item with a greater value in the orderby column that is nearest this one
    $query = static::prepareSQL(
      'SELECT *
      FROM %table
      WHERE %orderby_col > ?
      ORDER BY %orderby_col
      LIMIT 1'
    );

    $query->execute([$this->{$this->orderby}]);
    return $query->fetch();
  }



  protected function fetchAdjacentLesser(){
    # Select the item with a lower value in the orderby column that is nearest this one
    $query = static::prepareSQL(
      'SELECT *
      FROM %table
      WHERE %orderby_col < ?
      ORDER BY %orderby_col DESC
      LIMIT 1'
    );

    $query->execute([$this->{$this->orderby}]);
    return $query->fetch();
  }



  public function fetchNext(){
    if ($this->order_asc){
      return $this->fetchAdjacentGreater();
    }
    else{
      return $this->fetchAdjacentLesser();
    }
  }



  public function fetchPrev(){
    if ($this->order_asc){
      return $this->fetchAdjacentLesser();
    }
    else{
      return $this->fetchAdjacentGreater();
    }
  }
}




?>
