<?php

class View {

  public static function make($view) {
		require_once( './includes/controllers/'.$view.'.php' );
        require_once( './includes/views/'.$view.'.php' );
        return 1;
  }

}

?>
