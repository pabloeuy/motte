#!/bin/bash

# finish <message>
# if a message is received the program finishes
#
function finish {
	cd $PREVPATH
        if [ "$1" == "help" ]; then
                shift
                message "$1" 1
                message "newApp - Create a new Motte based application"
                message "Motte v1.0 <http://motte.codigolibre.net> <http://linuxteros.codigolibre.net/proyectos/motte>\nThis program is licensed under GPLv2+(http://opensource.org/licenses/gpl-license.php GNU Public license)\n\nAuthors:\tPedro Gauna, Carlos agliardi, Jose Dodero, GBoksar/Perro, Pablo Erartes" 1
                message "Parameters: (order is irrelevant)\n\t--help or -h or ?\tDisplay this text"
                message "Usage:\t$0" 1
                if [ "$1" != "" ]; then
                        exit 1
                else
                        exit 0
		fi
        else
                message "$1" 1
                exit 1
        fi
}


# message <text> [1|2]
# show runtime messages if verbose option was set (-v or --verbose)
# 1- leaves an empty line after the text
# 2- leaves an empty line before and after the text
#
function message {
	if [ "$2" == "2"  ]; then echo ""; fi
	echo -e "$1"
	if [ "$2" == "2"  ] || [ "$2" == "1"  ]; then echo ""; fi
}

#
# Create main configuration files
#
function createMainConfig {
echo -e "<?php
/**
 * Basic System Configuration
 *
 * @filesource
 * @author Motte Core Team
 * @url  http://motte.codigolibre.net
 * @package TestPkg
 * @subpackage publicFrontEnd
 * @version 0.1a
 * @license http://opensource.org/licenses/gpl-license.php GNU Public license (GPLv2+)
 */

define('MOTTE', ROOT_DIR.'/motte');
define('MTE_LANG', 'es'); // Optional - Default is 'en'

include_once(MOTTE.'/motte.inc.php');
?>" > cfg.motte.php

if [ "$USEDB" == "y" ]; then
echo -e "<?php
/**
 * Configuracion de la aplicacion
 *
 * @filesource
 * @author Sistemas INDT / Pablo Erartes (pabloeuy@gmail.com) / Gustavo Boksar (gustavo@boksar.info)
 * @package sindome
 * @subpackage aplicacion
 * @version 2.0
 * @license http://opensource.org/licenses/gpl-license.php GNU Public license (GPLv2+)
 */

define('MTE_DB_DRIVER', '$DBENGINE');
define('MTE_DB_HOST', '$DBHOST');
define('MTE_DB_USER', '$DBUSER');
define('MTE_DB_PASS', '$DBPASSWORD');
define('MTE_DB_NAME', '$DBNAME');

?>" > db_connect_data.php
fi

echo -e "<?php
/**
 * Configuracion de la aplicacion
 * 
 * @filesource
 * @author Sistemas INDT / Pablo Erartes (pabloeuy@gmail.com) / Gustavo Boksar (gustavo@boksar.info) 
 * @package sindome
 * @subpackage aplicacion
 * @version 2.0
 * @license http://opensource.org/licenses/gpl-license.php GNU Public license (GPLv2+) 
 */

define('ROOT_DIR','.');

define('MODULE_DIR',ROOT_DIR);

define('MODULE_NAME','Motte Application');

define('MTE_MODULE_TITLE','Main Module');
" > cfg.app.php

if [ "$USEDB" == "y" ]; then
	echo -e "include_once('db_connect_data.php');"  >> cfg.app.php
fi

echo -e "include('cfg.motte.php');
?>" >> cfg.app.php
}


#
# Create Basic Index
#
function createBasicIndex {
echo -e "<?php
/**
 * Basic Application for a simple table managing
 *
 * @filesource
 * @author Motte Core Team
 * @url  http://motte.codigolibre.net
 * @package TestPkg
 * @subpackage publicFrontEnd
 * @version 0.1a
 * @license http://opensource.org/licenses/gpl-license.php GNU Public license (GPLv2+)
 */
 
include('cfg.app.php');
 
\$app = new mteController();
\$app->addConfig(MODULE_NAME);
\$app->createSession();
\$app->connectDB();
 
\$app->validateUrl();

\$pag = \$app->generateHtmlPage();
\$pag->setContent('Hola Motte!');
\$pag->showHtml();
?>" > index.php
}


#
# ----- Logic
#
#


case "$1" in
	--help | -h | ?)
		finish help
		;;
	--usage)
		finish "Usage:\n\tnewApp.sh"
esac

if [ "$2" == "" ]; then
	read -a APPNAME -p "Ingrese nombre de la aplicación[mteTest]: "
	if [ "$APPNAME" == "" ]; then
		APPNAME="mteTest"
	fi

fi

read -a DIRBASE -p "Ingrese directorio base[.]: "
if [ "$DIRBASE" == "" ]; then
	DIRBASE="."
fi

if [ -w $DIRBASE ]; then 
	mkdir ${DIRBASE}/${APPNAME}
	if [ "$?" -gt "0" ]; then
		finish help "Something goes wrong during creation of folder.\nEither you don't have writing privileges on base folder or your disk is out of space...\nUnable to continue!"
	fi
else
	finish help "You don't have writing privileges on this folder... Unable to continue!"
fi

PREVPATH=$(pwd)
cd ${DIRBASE}/${APPNAME}
if [ "$?" -gt "0" ]; then
	finish help "Can't entre new folder. Ensure you hace read privileges on base folder.\nUnable to continue!"
fi
mkdir -p model docs templates/$APPNAME data cache view
if [ "$?" -gt "0" ]; then
	finish help "Something goes wrong during creation of folder.\nEither you don't have writing privileges on base folder or your disk is out of space...\nUnable to continue!"
fi

read -a USEDB -p "Do your application connect to a database[y]?"

if [ "$USEDB" == "y" ] || [ "$USEDB" == "Y" ] || [ "$USEDB" == "" ]; then
	USEDB="y"
fi

if [ "$USEDB" == "y" ]; then
	message "Entre database connection data, if not sure, leave it empty" 2
	read -a DBNAME -p "Database Name: "
	read -a DBHOST -p "Database Host[localhost]: "
	read -a DBUSER -p "Database User: "
	read -s -a DBPASSWORD -p "Database Password: "
	echo ""
	read -a DBENGINE -p "Database Engine[MySql]: "
	if [ "$DBHOST" == "" ]; then
		DBHOST="localhost"
	fi
	if [ "$DBUSER" == "" ]; then
		DBUSER="replace_with_your_db_user"
	fi
	if [ "$DBPASSWORD" == "" ]; then
		DBPASSWORD="replace_with_your_userpassword"
	fi
	if [ "$DBNAME" == "" ]; then
		DBHOST="replace_with_your_database"
	fi
	if [ "$DBENGINE" == "" ]; then
		DBENGINE="MySql"
	fi
fi

read -a PATHTOMOTTE -p "Path to Motte(relative to application directory): "
if [ "$PATHTOMOTTE" != " " ]; then
	ln -s ${PATHTOMOTTE} motte
fi
createMainConfig
createBasicIndex

message "Remember:" 2

if [ "$USEDB" == "y" ]; then
	message "  - to execute mysql2motte.sh for automatic generation of your table classes\nor create your classes manually inside \"model\" directory."
fi

message "  - to set application folder with the right privileges depending on your system!" 1
message "                                  Enjoy Motte & PHP! Always better with Free Software ;)" 2

cd $PREVPATH
exit 0