<?php

//Incluimos el archivo de configuracion
include 'config.php';

class dbConect {

  public $conex;

  //Hace la conexion a la base
  function __construct(){
    $this->conex = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die('Ocurrio un error al conectarse con la base');
    return $this->conex;
  }

  //Obtendra datos de la base
  function get_query($sql){
    $data = $this->conex->query($sql);
    while ( $rows[] = $data->fetch_assoc());
    if (count($rows)) {
      foreach ($rows as $idx => $item) {
        if (empty($item)) unset($rows[$idx]);
      }
    }
    return $rows;
  }

  //Realizara cambios en la base
  function post_query($sql){
    $data = $this->conex->query($sql);
    return $data;
  }

  //Termina la conexion
  function __desctruct(){
    $this->conex->close();
  }

}

?>
