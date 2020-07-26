<?php
  Route::set('index.php', function() {
	  View::make('Root');
  });
  Route::set('register.php', function() {
  	View::make('Register');
  });
  Route::set('login.php', function() {
  	View::make('Login');
  });
    Route::set('logout.php', function() {
  	View::make('Logout');
  });

?>