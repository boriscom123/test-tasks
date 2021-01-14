<?php
  class Database
  {
    /**
    *
    */
    function __construct($path = 'database')
    { // задаем папку для хранения файлов базы данных
      $this->path = $path;
      if(!file_exists($this->path.'/tables.json')){
        file_put_contents($this->path.'/tables.json', '', FILE_APPEND | LOCK_EX);
      }
    }

    public function dBaseRequest($params)
    {
      if(empty($params))
      {
        return "Пустой запрос";
      }
      else
      { // Обрабатывам входящие параметры строки
        if (preg_match('/CREATE TABLE /i', $params))
        {
          $this->name = preg_replace('/CREATE TABLE /i', '', $params);
          // echo "Добавляем таблицу:".$this->name;
          $this->tableCreate($this->name);
          return $this->message;
        } else if (preg_match("/SELECT FROM /i", $params))
        {
          echo "Выводим данные из таблицы";
        } else if (preg_match("/INSERT INTO /i", $params))
        {
          echo "Добавляем данные в таблицу";
          $this->insert = explode(' ', preg_replace('/INSERT INTO /i', '', $params));
          // print_r($this->insert);
          $this->table_name = $this->insert['0'];
          // pop до получения VALUE
          $this->insert = strstr($params, ' VALUES ', );
          $this->insert = explode(' ', preg_replace('/ VALUES /i', '', $this->insert));
          print_r($this->insert);

          // foreach($this->insert as $key => $value){
          //   echo 'Ключ: '. $key . ' Значение: '.$value;
          // }
        } else
        {
          $this->message = 'Некорректный запрос';
          return $this->message;
        }
      }
    }

    private function getFieldNameById($table_id, $field_id)
    {
      $structure = json_decode(file_get_contents('database/structure/'.$base->id.'.json'));
      foreach($structure as $field){
        if($field_id == $field->id){
          return $field->name;
        } else {
          return false;
        }
      }
    }

    private function tableCreate()
    { // Создаем новую таблицу
      $bases = json_decode(file_get_contents('database/tables.json')); // запрашиваем текущие базы
      $max_id = 0;
      if(!empty($bases))
      {
        foreach($bases as $base)
        {
          if($base->id > $max_id)
          {
            $max_id = $base->id;
          }
        }
      }
      // echo "Максимальный ID: ".$max_id;
      $new_base = new stdClass();
      $new_base->id = ++$max_id;
      $new_base->name = $this->name;
      $bases[] = $new_base;
      $handle = fopen('database/tables.json', 'w');
      fwrite($handle, json_encode($bases));
      fclose($handle);
      // добавляем файл структуры БД
      $structure = fopen('database/structure/'.$new_base->id.'.json', 'w');
      fclose($structure);
      // добавляем файл контента БД
      $content = fopen('database/content/'.$new_base->id.'.json', 'w');
      fclose($content);
      $this->message = 'Таблица "'. $this->name .'" успешно создана';
    }

    public function tableFieldCreate($id, $name)
    {
      // echo 'Создаем поле "'.$name.'" для таблицы с ID: '.$id;
      $structure = json_decode(file_get_contents('database/structure/'.$id.'.json'));
      $max_id = 0;
      if(!empty($structure))
      {
        foreach($structure as $field)
        {
          if($field->id > $max_id)
          {
            $max_id = $field->id;
          }
        }
      }
      $new_field = new stdClass();
      $new_field->id = ++$max_id;
      $new_field->name = $name;
      $structure[] = $new_field;
      $handle = fopen('database/structure/'.$id.'.json', 'w');
      fwrite($handle, json_encode($structure));
      fclose($handle);
      $this->message = 'Поле "'. $new_field->name .'" для таблицы "'. $id .'" успешно создано';
      return $this->message;
    }

    private function tableInsert($params)
    { // Вставляем данные в таблицу

    }

    private function tableSelect($params)
    { // Выводим данные из таблицы

    }

    public function showAllTables()
    {
      $tables = json_decode(file_get_contents('database/tables.json'));
      return $tables;
    }

    public function showTableContent($id)
    {
      $bases = json_decode(file_get_contents('database/tables.json'));
      foreach($bases as $base)
      {
        if($id == $base->id)
        {
          $data[] = $base;
          $structure = json_decode(file_get_contents('database/structure/'.$base->id.'.json'));
          if(!empty($structure))
          {
            $data[] = $structure;
          }
          $content = json_decode(file_get_contents('database/content/'.$base->id.'.json'));
          if(!empty($content))
          {
            $data[] = $content;
          }
          return $data;
        }
      }
    }
  }
  $db = new Database();

  if(isset($_REQUEST['new-table']) || isset($_GET['new-table']) || isset($_POST['new-table'])) {
    $show_form = 'show_new_table_form';
  }
  if(isset($_REQUEST['create-table']) || isset($_GET['create-table']) || isset($_POST['create-table'])) {
    $message = $db->dBaseRequest('CREATE TABLE '.$_REQUEST['name']);
  }
  if(isset($_REQUEST['db-request']) || isset($_GET['db-request']) || isset($_POST['db-request'])) {
    $message = $db->dBaseRequest($_REQUEST['db-request']);
  }
  if(isset($_REQUEST['table-id']) || isset($_GET['table-id']) || isset($_POST['table-id'])) {
    $content = $db->showTableContent($_REQUEST['table-id']);
  }
  if(isset($_REQUEST['create-field']) || isset($_GET['create-field']) || isset($_POST['create-field'])) {
    if($_REQUEST['name'] != ''){
      $message = $db->tableFieldCreate($_REQUEST['create-field'], $_REQUEST['name']);
      $content = $db->showTableContent($_REQUEST['create-field']);
    }
  }
  if(isset($_REQUEST['insert-value']) || isset($_GET['insert-value']) || isset($_POST['insert-value'])) {
    if(isset($_REQUEST['name'])) {
      $message = $db->dBaseRequest('INSERT INTO '.$_REQUEST['name']);
    } else {
      $tables = json_decode(file_get_contents('database/tables.json'));
      $table_name = '';
      foreach($tables as $table){
        if($_REQUEST['insert-value'] == $table->id){
          $table_name = $table->name;
        }
      }
      $values = '';
      foreach($_REQUEST as $key => $value){
        $key = preg_replace('/field-id-/i', '', $key );
        $structure = json_decode(file_get_contents('database/structure/'.$_REQUEST['insert-value'].'.json'));
        foreach($structure as $field){
          if($key == $field->id){
            $values .= $field->name.'=:'.$value.', ';
          }
        }
      }
      $values = rtrim($values, ', ');
      $request_value = 'INSERT INTO '.$table_name.' VALUES '.$values;
      // echo $request_value;
      $message = $db->dBaseRequest($request_value);
    }
  }
?>
<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>База Данных</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="style/index.css">
  </head>
  <body>

    <header class="header">

      <form class="table-add container row mx-auto py-3" action="index.php" method="get">
        <button class="btn btn-primary row g-0 py-2 fs-4 fs-sm-3" type="submit" name="new-table">Создать новую таблицу</button>
      </form>

    </header>

    <main class="main container row mx-auto py-3">

      <form class="table-list col-12 col-sm-3 row flex-column mx-auto" action="index.php" method="get">

        <div class="table-title fs-5 fs-sm-4 fs-lg-3 text-center py-1">Список таблиц</div>

        <?php
          // Выводим существующие таблицы
          $all_tables_name = $db->showAllTables();
          if(!empty($all_tables_name)) {include 'includes/tables.php';}
        ?>

      </form>

      <div class="table-content col-12 col-sm-9 py-3">

        <div class="">
          <form class="d-flex flex-column justify-content-end mx-auto" action="index.php" method="get">
            <p>Введите запрос к базе данных. Например: CREATE TABLE {table_name}</p>
            <textarea class="w-100 p-3" name="db-request" placeholder="CREATE TABLE {table_name}"></textarea>
            <button class="w-50 btn btn-success align-self-end my-2" type="submit" name="send-request">Отправить</button>
          </form>
        </div>

        <?php
          if(isset($message)) {include 'includes/message.php';}
          if(isset($show_form)) {include 'includes/create-table.php';}
          if(isset($content)) {include 'includes/content.php';}
        ?>

        <form class="row flex-column align-content-center py-3 d-none" action="index.php" method="get">
          <input class="col-12 col-sm-6 fs-3 py-1" type="text" name="name" value="" placeholder="Введите название поля">
          <button class="col-12 col-sm-6 btn btn-success fs-3 my-2 py-2" type="submit" name="add-field">Добавить</button>
        </form>

      </div>

    </main>

    <footer class="footer container row mx-auto py-3 fixed-bottom">
      <a href="index.php">@Bynextpr - Борис Полянский</a>
    </footer>

  </body>
</html>
