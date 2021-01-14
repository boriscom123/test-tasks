<?php
  if($show_form == 'show_new_table_form')
  {
    ?>
    <form class="row flex-column align-content-center py-3" action="index.php" method="get">
      <input class="col-12 col-sm-6 fs-3 py-1" type="text" name="name" value="" placeholder="Введите название таблицы">
      <button class="col-12 col-sm-6 btn btn-success fs-3 my-2 py-2" type="submit" name="create-table">Создать</button>
    </form>
    <?php
  }
?>
