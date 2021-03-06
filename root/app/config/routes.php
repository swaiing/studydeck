<?php
/* SVN FILE: $Id: routes.php 7945 2008-12-19 02:16:01Z gwoo $ */
/**
 * Short description for file.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @version       $Revision: 7945 $
 * @modifiedby    $LastChangedBy: gwoo $
 * @lastmodified  $Date: 2008-12-18 18:16:01 -0800 (Thu, 18 Dec 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
/**
 * Map /gre/lists to products/view
 */
	Router::connect('/gre/lists', array('controller' => 'products', 'action' => 'view'));
/**
 * Map /features/* to static tour pages
 */
	Router::connect('/features', array('controller' => 'pages', 'action' => 'display', 'overview'));
	Router::connect('/features/categorize', array('controller' => 'pages', 'action' => 'display', 'categorize'));
	Router::connect('/features/quiz', array('controller' => 'pages', 'action' => 'display', 'quiz'));
	Router::connect('/features/track', array('controller' => 'pages', 'action' => 'display', 'track'));
	Router::connect('/features/create', array('controller' => 'pages', 'action' => 'display', 'create'));
/**
 * Footer main links
 */
	Router::connect('/about', array('controller' => 'pages', 'action' => 'display', 'about'));
	Router::connect('/contact', array('controller' => 'pages', 'action' => 'display', 'contact'));
	Router::connect('/tos', array('controller' => 'pages', 'action' => 'display', 'tos'));
	Router::connect('/privacy', array('controller' => 'pages', 'action' => 'display', 'privacy'));

?>
