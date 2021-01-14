<?php
  if($show_form == 'show_new_table_form')
  {
    ?>
    <form class="d-flex flex-column py-3" action="index.php" method="get">
      <input class="col-12 col-sm-6 fs-5 py-1 px-1" type="text" name="name" value="" placeholder="Введите название таблицы">
      <button class="col-12 col-sm-3 btn btn-success align-self-end fs-5 my-2 py-2" type="submit" name="create-table">Создать</button>
    </form>
    <?php
  }
?>
