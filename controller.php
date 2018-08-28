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
            foreach ($res as $idx => $val) {
              if ($bt == "dependencias") {
                $this->data[] = array(
                  "uuid" => $val["uuid"],
                  "dependencia" => $val["nombre"],
                  "codigo" => "200 Ok",
                  "success" => true
                );
              }else{
                $this->data[] = array(
                  "uuid" => $val["uuid"],
                  "nombre" => $val["nombre"],
                  "apellido_paterno" => $val["primer_apellido"],
                  "apellido_materno" => $val["segundo_apellido"],
                  "cargo" => $val["cargo"],
                  "email" => $val["email"],
                  "fecha_nacimiento" => $val["fecha_nacimiento"]
                );
              }
            }
            break;
          case 'POST':
            $res = $this->modifica_datos($bt,$id);
            if ($res) {
              $this->data = array(
                "codigo" => "201 Created",
                "success" => true
              );
            }
            break;
        }
        die( json_encode($this->data) );
      }
    }
    die( json_encode(["error" => "404 Not Found"]) );
  }

  function obtiene_datos($tabla="",$id=null){
    $idx = filter_var($id,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $filter = $id ? " AND uuid = '${idx}' " : '';
    $query = "
              SELECT *
              FROM ${tabla}
              WHERE isDelete = 0
              ${filter}
            ";
    return $this->cc->get_query($query);
  }

  function modifica_datos($tabla="",$filt=1){
    $labels = array(
                    "name_depend" => "nombre",
                    "name_aut" => "nombre",
                    "paterno_aut" => "primer_apellido",
                    "materno_aut" => "segundo_apellido",
                    "fecha_aut" => "fecha_nacimiento",
                    "puesto_aut" => "cargo",
                    "email_aut" => "email",
                    "sel_depend" => "uuid",
                    "supr" => "isDelete"
              );
    $send = array();
    $opt = array(
      'name_depend' =>FILTER_SANITIZE_STRING|FILTER_SANITIZE_FULL_SPECIAL_CHARS,
      'name_aut' => FILTER_SANITIZE_STRING|FILTER_SANITIZE_FULL_SPECIAL_CHARS,
      'paterno_aut' => FILTER_SANITIZE_STRING|FILTER_SANITIZE_FULL_SPECIAL_CHARS,
      'materno_aut' => FILTER_SANITIZE_STRING|FILTER_SANITIZE_FULL_SPECIAL_CHARS,
      'puesto_aut' => FILTER_SANITIZE_STRING|FILTER_SANITIZE_FULL_SPECIAL_CHARS,
      'sel_depend' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
      'fecha_aut' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
      'email_aut' => FILTER_SANITIZE_EMAIL,
    );
    $tmp = filter_var_array($_POST,$opt);
    foreach ($tmp as $key => $value) {
      $value && ($send[$labels[$key]] = $value);
    }
    if ($tabla == "dependencias" && $filt == "1") {
      $send["uuid"] = md5($send['nombre'].date('Ymd'));
    }
    if ($filt == '1') $result = $this->cc->set_query($tabla,$send,'insert');
    else $result = $this->cc->set_query($tabla,$send,'update');
    return $result;
  }


}


?>
