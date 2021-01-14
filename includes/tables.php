<?php
  foreach($all_tables_name as $table){
    echo '<button class="btn btn-secondary my-1 py-1 mx-0" type="submit" name="table-id" value="'.$table->id.'">'.$table->name.'</button>';
  }
?>
