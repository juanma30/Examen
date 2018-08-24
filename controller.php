<?php

include 'dbConect.php';

class controller{

  //Variable que tendra la conexion a la base de datos
  public $cc;

  //Variable que contendra los datos de resultado
  public $data;

  function __construct(){
    $this->cc = new dbConect();
  }

  function init_controller($metodo,$data){
    if (count($data)>1) {
      count($data) == 3 && ($id = array_pop($data));
      $bt = array_pop($data);
      if (is_string($bt) && in_array($bt,["dependencias","autoridades"])) {
        switch ($metodo) {
          case 'GET':
            $res = $this->obtiene_datos($bt,isset($id) ? $id : null);
            break;
          case 'POST':
            $res = $this->modifica_datos($bt,isset($id) ? $id : null);
            break;
        }
        foreach ($res as $idx => $val) {
          $this->data[] = array(
                            "uuid" => $val["uuid"],
                            "dependencia" => $val["nombre"]
          );
        }
        die( json_encode($this->data) );
      }
    }
    die( json_encode(["error" => "404 Not Found"]) );
  }

  function obtiene_datos($tabla="",$id=null){
    $idx = filter_var($id,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $filter = $id ? " WHERE uuid = '${idx}' " : '';
    $query = "
              SELECT *
              FROM ${tabla}
              ${filter}
            ";

    return $this->cc->get_query($query);
  }

  function modifica_datos($tabla="",$id=null){

  }


}


?>
