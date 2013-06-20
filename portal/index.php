<?php
/**
 * Motte Welcome App
 *
 * @filesource
 * @author Motte Core Team
 * @url  http://motte.codigolibre.net
 * @package MottePkg
 * @subpackage publicFrontEnd
 * @version 0.1a
 * @license http://opensource.org/licenses/gpl-license.php GNU Public license (GPLv2+)
 */

    include('cfg.motte.php');
    $app = new mteController();	// Creates a new Motte App
    $app->addConfig();		// Create a PHP session and starts varibales

    $app->validateUrl();		// check if URL was not compromissed

    $content = $app->generateHtmlException($template);
    $content->setTitle(__('Welcome to')." Motte");
    $content->setProblem(__('Motte is a PHP5 framework for quick application developement.'));
    $content->setExplanation(__('Despite being in a testing stage, Motte is completely usable and you\'re welcome to contribute!'));
    $content->setVeredict(__('Visit our <a href="http://motte.codigolibre.net">web page</a> to learn more...'));

    $channels = $app->generateHtmlChannel();
    $channels->addChannel('Motte', 'Motte', 'http://motte.codigolibre.net', '_blank');
    $channels->addChannel('LinuxTeros', 'LinuxTeros', 'http://linuxteros.codigolibre.net', '_blank');

    $page = $app->generateHtmlPage();
    $page->setChannels($channels->fetchHtml());	// Inserts added channels to page
    $page->setContent($content->fetchHtml());	// Inserts content to page body
    $page->showHtml();				// Draw page

?>
