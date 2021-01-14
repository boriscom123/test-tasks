<?php
  class Database
  {

    function __construct($path = 'database')
    {
      $this->path = $path;
      if(!file_exists($this->path.'/tables.json')){
        file_put_contents($this->path.'/tables.json', '', FILE_APPEND | LOCK_EX);
      }
      if(!file_exists($this->path.'/uid.json')){
        file_put_contents($this->path.'/uid.json', '', FILE_APPEND | LOCK_EX);
      }
    }

    public function dBaseRequest($params)
    {
      if(empty($params))
      {
        return "Пустой запрос";
      }
      else
      {
        $this->query = $params;
        if (preg_match('/CREATE TABLE /i', $params))
        {
          $this->name = preg_replace('/CREATE TABLE /i', '', $params);
          $this->tableCreate($this->name);
          return $this->message;
        } else if (preg_match("/SELECT FROM /i", $params))
        {
          $this->name = preg_replace('/SELECT FROM /i', '', $params);
          return $this->tableSelect($this->name);
        } else if (preg_match("/INSERT INTO /i", $params))
        {
          $this->insert = explode(' ', preg_replace('/INSERT INTO /i', '', $params));
          $this->table_name = $this->insert['0'];
          $this->table_id = $this->getTableIdByName($this->insert['0']);
          $content = json_decode(file_get_contents('database/content/'.$this->table_id.'.json'));
          $this->insert = strstr($params, ' VALUES ', );
          $this->insert = explode(', ', preg_replace('/ VALUES /i', '', $this->insert));
          $new_value = new stdClass();
          foreach($this->insert as $value){
            $value = explode('=:', $value);
            if(empty($value['1'])) {
              $value['1'] = 'null';
            }
            $new_value->{$this->getFieldIdByName($this->table_id, $value['0'])} = $value['1'];
          }
          $content[] = $new_value;
          file_put_contents('database/content/'.$this->table_id.'.json', json_encode($content), LOCK_EX);
        } else
        {
          $this->message = 'Некорректный запрос';
          return $this->message;
        }
      }
    }

    private function getTableIdByName($table_name)
    {
      $tables = json_decode(file_get_contents('database/tables.json'));
      foreach($tables as $table){
        if($table_name == $table->name){
          return $table->id;
        }
      }
      return false;
    }

    public function getTableNameById($table_id)
    {
      $tables = json_decode(file_get_contents('database/tables.json'));
      foreach($tables as $table){
        if($table_id == $table->id){
          return $table->name;
        }
      }
      return false;
    }

    private function getFieldNameById($table_id, $field_id)
    {
      $structure = json_decode(file_get_contents('database/structure/'.$base->id.'.json'));
      foreach($structure as $field){
        if($field_id == $field->id){
          return $field->name;
        }
      }
      return false;
    }

    private function getFieldIdByName($table_id, $field_name)
    {
      $structure = json_decode(file_get_contents('database/structure/'.$table_id.'.json'));
      foreach($structure as $field){
        if($field_name == $field->name){
          return $field->id;
        }
      }
      return false;
    }

    private function tableCreate()
    {
      $tables = json_decode(file_get_contents('database/tables.json'));
      $max_id = 0;
      if(!empty($tables))
      {
        foreach($tables as $table)
        {
          if($table->id > $max_id)
          {
            $max_id = $table->id;
          }
        }
      }
      $new_table = new stdClass();
      $new_table->id = ++$max_id;
      $new_table->name = $this->name;
      $tables[] = $new_table;
      file_put_contents('database/tables.json', json_encode($tables), LOCK_EX);
      file_put_contents('database/structure/'.$new_table->id.'.json', '', LOCK_EX);
      file_put_contents('database/content/'.$new_table->id.'.json', '', LOCK_EX);
      $this->message = 'Таблица "'. $this->name .'" успешно создана';
    }

    public function tableFieldCreate($table_id, $name)
    {
      $structure = json_decode(file_get_contents('database/structure/'.$table_id.'.json'));
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
      file_put_contents('database/structure/'.$table_id.'.json', json_encode($structure), LOCK_EX);
      $this->message = 'Поле "'. $new_field->name .'" для таблицы "'. $this->getTableNameById($table_id) .'" успешно создано';
      $content = json_decode(file_get_contents('database/content/'.$table_id.'.json'));
      if(!empty($content)){
        foreach($content as $value){
          $value->{$new_field->id} = 'null';
          $new_content[] = $value;
        }
        file_put_contents('database/content/'.$table_id.'.json', json_encode($new_content), LOCK_EX);
      }
      return $this->message;
    }

    private function tableSelect($table_name)
    {
      $tables = json_decode(file_get_contents('database/tables.json'));
      foreach($tables as $table)
      {
        if($table_name == $table->name)
        {
          $data[] = $table;
          $structure = json_decode(file_get_contents('database/structure/'.$table->id.'.json'));
          if(!empty($structure))
          {
            $data[] = $structure;
          }
          $content = json_decode(file_get_contents('database/content/'.$table->id.'.json'));
          if(!empty($content))
          {
            $data[] = $content;
          }
          return $data;
        }
      }
    }

    public function showAllTables()
    {
      $tables = json_decode(file_get_contents('database/tables.json'));
      return $tables;
    }

    public function showTableContent($table_id)
    {
      $tables = json_decode(file_get_contents('database/tables.json'));
      foreach($tables as $table)
      {
        if($table_id == $table->id)
        {
          $data[] = $table;
          $structure = json_decode(file_get_contents('database/structure/'.$table->id.'.json'));
          if(!empty($structure))
          {
            $data[] = $structure;
          }
          $content = json_decode(file_get_contents('database/content/'.$table->id.'.json'));
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
    $content = $db->dBaseRequest($_REQUEST['db-request']);
  }
  if(isset($_REQUEST['table-id']) || isset($_GET['table-id']) || isset($_POST['table-id'])) {
    $content = $db->dBaseRequest('SELECT FROM '.$db->getTableNameById($_REQUEST['table-id']));
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
          $all_tables_name = $db->showAllTables();
          if(!empty($all_tables_name)) {include 'includes/tables.php';}
        ?>

      </form>

      <div class="table-content col-12 col-sm-9 py-3">

        <div class="">
          <form class="d-flex flex-column mx-auto" action="index.php" method="get">
            <?php
              if(empty($_REQUEST)){
                echo '<p>Введите запрос к базе данных. Например: CREATE TABLE {table_name}</p>';
                echo '<textarea class="w-100 p-3" name="db-request" placeholder="CREATE TABLE {table_name}"></textarea>';
              } else {
                echo '<p>Введите запрос к базе данных. Например: INSERT INTO {table_name} VALUES {name1}=:{value1}, {name2}=:{value2}</p>';
                echo '<textarea class="w-100 p-3" name="db-request" placeholder="'.$db->query.'"></textarea>';
              }
            ?>
            <button class="col-12 col-sm-3 btn btn-success align-self-end fs-5 my-2" type="submit" name="send-request">Отправить</button>
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
