<?php
class Todo {

  public function getRoute() {

  	global $Routes;
    $uri = $_SERVER['REQUEST_URI'];

    return $uri;

  }

  public function run() {

        $this->getRoute();
  }

}
?>