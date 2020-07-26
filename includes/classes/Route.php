<?php
class Route{
	public static $validRoutes = array();

	  public function isRouteValid() {
	    global $Routes;
	    $uri = $_SERVER['REQUEST_URI'];
	    
	  }

	public static function set($route,$function){
		self::$validRoutes[] = $route;
		if($_GET['url']== $route){
			$function->__invoke();
		}
	}
}
?>