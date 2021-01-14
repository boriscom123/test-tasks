<?php
  // print_r($content);
  if(count($content) == 1){
    echo 'Таблица "'.$content['0']->name.'" - пустая. Добавьте необходимые поля.';
    ?>
      <form class="row flex-column align-content-center py-3" action="index.php" method="get">
        <input class="col-12 col-sm-6 fs-3 py-1" type="text" name="name" value="" placeholder="Введите название поля">
        <button class="col-12 col-sm-6 btn btn-success fs-3 my-2 py-2" type="submit" name="create-field" value="<?php echo $content['0']->id;?>">Создать</button>
      </form>
    <?php
  } else {
    // echo "В таблице есть данные";
    ?>
      <form class="row py-1" action="index.php" method="get">
        <div class="d-flex flex-column flex-nowrap overflow-auto py-3">
          <?php
          echo '<div class="d-flex flex-nowrap">';
          foreach($content['1'] as $field_title)
          {
            echo '<input class="mx-0 px-1 w-25 fs-5 py-1 text-center" type="text" name="field-id-'.$field_title->id.'" value="'.$field_title->name.'" disabled>';
          }
          echo '</div>';
          echo '<div class="d-flex flex-nowrap">';
          foreach($content['1'] as $field_title)
          {
            echo '<input class="mx-0 px-1 w-25 fs-5 py-1" type="text" name="field-id-'.$field_title->id.'" value="'.$field_title->name.'" disabled>';
          }
          echo '</div>';
          echo '<div class="d-flex flex-nowrap">';
          foreach($content['1'] as $field_title)
          {
            echo '<input class="mx-0 px-1 w-25 fs-5 py-1" type="text" name="field-id-'.$field_title->id.'" value="">';
          }
          echo '</div>';
          ?>
        </div>
        <div class="d-flex justify-content-end">
          <button class="col-12 col-sm-3 btn btn-success fs-5 my-2 py-2" type="submit" name="insert-value" value="<?php echo $content['0']->id;?>">Добавить</button>
        </div>

      </form>

      <form class="row flex-column align-content-center py-3" action="index.php" method="get">
        <input class="col-12 col-sm-6 fs-3 py-1" type="text" name="name" value="" placeholder="Введите название поля">
        <button class="col-12 col-sm-6 btn btn-success fs-3 my-2 py-2" type="submit" name="create-field" value="<?php echo $content['0']->id;?>">Создать</button>
      </form>
    <?php
  }
?>
