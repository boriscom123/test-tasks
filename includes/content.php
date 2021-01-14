<?php
  // print_r($content);
  if(count($content) == 1){
    echo '<p>Таблица "'.$content['0']->name.'" - пустая. Добавьте необходимые поля.</p>';
    ?>
      <form class="d-flex flex-column py-3" action="index.php" method="get">
        <input class="col-12 col-sm-6 fs-5 py-1 px-1" type="text" name="name" value="" placeholder="Введите название поля">
        <button class="col-12 col-sm-3 btn btn-success align-self-end fs-5 my-2 py-2" type="submit" name="create-field" value="<?php echo $content['0']->id;?>">Создать</button>
      </form>
    <?php
  } else {
    echo '<p>Данные таблицы: "'.$content['0']->name.'"</p>';
    ?>
      <form class="row py-1" action="index.php" method="get">
        <div class="d-flex flex-nowrap justify-content-start overflow-auto py-3">
          <?php

          foreach($content['1'] as $field_title)
          {
            echo '<div class="d-flex flex-column flex-nowrap w-25">';
            echo '<input class="mx-0 px-1 fs-5 py-1 text-center" type="text" name="field-id-'.$field_title->id.'" value="'.$field_title->name.'" disabled>';
            if(isset($content['2'])){
              foreach($content['2'] as $field_content)
              {
                echo '<input class="mx-0 px-1 fs-5 py-1" type="text" name="field-id-'.$field_title->id.'" value="'.$field_content->{$field_title->id}.'" disabled>';
              }
            }
            echo '<input class="mx-0 px-1 fs-5 py-1" type="text" name="field-id-'.$field_title->id.'" value="">';
            echo '</div>';
          }
          ?>
        </div>
        <div class="d-flex justify-content-end">
          <button class="col-12 col-sm-3 btn btn-success fs-5 my-2 py-2" type="submit" name="insert-value" value="<?php echo $content['0']->id;?>">Добавить</button>
        </div>

      </form>
      <?php
        echo '<p>Добавьте поле:</p>';
      ?>
      <form class="d-flex flex-column py-3" action="index.php" method="get">
        <input class="col-12 col-sm-6 fs-5 py-1 px-1" type="text" name="name" value="" placeholder="Введите название поля">
        <button class="col-12 col-sm-3 btn btn-success align-self-end fs-5 my-2 py-2" type="submit" name="create-field" value="<?php echo $content['0']->id;?>">Создать</button>
      </form>
    <?php
  }
?>
