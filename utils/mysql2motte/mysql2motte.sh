#!/bin/bash

# Motte MySQL2Class Generator
# PHP Class Generator based on MySQL database structure
# Authors: Pedro Gauna: pgauna@gmail.com, Carlos Gagliardi: carlosgag@gmail.com,
#          Jose Dodero: jose.dodero@gmail.com, GBoksar\/Perro: gustavo@boksar.info),
#          Pablo Erartes: pabloeuy@gmail.com,
#          LinuxTeros: http://linuxteros.codigolibre.net/mottte, INDT Sistemas: http://www.indt.hc.edu.uy
# License: GPLv2+ (http://opensource.org/licenses/gpl-license.php GNU Public license)
# Link:    http://motte.codigolibre.net
# Last update: Wed Set 12 09:09:48 UYT 2007
#

VERBOSE=0
INCREMENTAL=0
DBUSER=""
DBPASS=""
DBHOST=""
DBNAME=""
DBHOSTPORT=""
DBHOST=""
CLSPATH="."
TBLSTRUCT=""
TPLFILE=""
PACKAGE="motte_project"
LICENSE="GPLv2+"
VERSION="1.0"
FUNCSERIAL=""
# finish <message>
# if a message is received the program finishes
#
function finish {
	eraseTmp
	VERBOSE=1

	if [ "$1" == "help" ]; then
		shift

		message "$1" 1
		message "MySQL2Motte - Mysql database parser for automagic creation of classes"
		message "Motte v1.0 <http://motte.codigolibre.net> <http://linuxteros.codigolibre.net/proyectos/motte>\nThis program is licensed under GPLv2+(http://opensource.org/licenses/gpl-license.php GNU Public license)\n\nAuthors:\tPedro Gauna, Carlos Gagliardi, Jose Dodero, GBoksar/Perro, Pablo Erartes" 1
		message "Parameters: (order is irrelevant)\n\tOptionals:\n\t\t-v  or  --verbose\n\t\t-i  or  --autoincremental\n\t\t-o=  or  --output=  <TargetDirectory>\n\t\t-t=  or  --template=  <template_file>\t(If none specified default Motte class template will be used)\n\t\t\tVariables to be replaced on template:\n\t\t\t\t@AUTHOR\t\tApplication authors.\n\t\t\t\t@URL\t\tApplication URL.\n\t\t\t\t@PACKAGE\tApplication package name.\n\t\t\t\t@DATE\t\tDate/time when class was created.\n\t\t\t\t@TBLSTRUCT\tOriginal DB table structure for the class.\n\t\t\t\t@INCLUDES\tOther classes needed for foreign keys validation and default values.\n\t\t\t\t@TABLE\t\tDB table name being processed.\n\t\t\t\t@CTR\t\tControls for NOT NULL fields.\n\t\t\t\t@INITIALIZE\tDefault values for each fields.\n\t\t\t\t@SERIAL\t\tFunction to obtain auto-numeric ID form serial table.\n\t\t\t\t@CTRI\t\tIntegrity checks.\n\t\t-a=  or  --author=  <authors>*\n\t\t-l=  or  --url=  <url>*\n\t\t-k=  or  --package=  <package_name>*\n\n\t\t\t*Can't contain special chars(\\ or \") or the should be escaped like \\\\\ and \\\".\n\n\tConnection Values:\n\t\t-u=  or  --user=  <BDuser>\n\t\t-p=  or  --passwd=  <BDpass>\n\t\t-d=  or  --database=  <BDname>\n\n\tOptional Connection values:\n\t\t-h=  or  --host=  <BDhost>\n\t\t-P=  or  --port=  <BDhostport>" 1
		message "\t\t\-x=  or --export=  <file>\tExports default Motte class template to specified file. If none is specified will be exported to \"mteTpl.mte\"." 1
		message "Syntax:\t$0 [-v] [-i] -u=<user> -p=<passwd> -d=<database> [-h=<host_ip>] [-P=<host_port>] [-o=<output_directory>] [-t=<my_template_file>] [-a=<author_name>] [-l=<app_url>] [-k=<package_name>] [--ver=<version>]\nor\n\t$0 -x[=<destination_file>]" 1
		message "Usage:\t$0 -v -u=myuser -p=mypass -d=testdb\n\t\tor\n\t$0 -x" 1
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
	if [ "$VERBOSE" == "1" ]; then
		if [ "$2" == "2"  ]; then echo ""; fi
		echo -e "$1"
		if [ "$2" == "2"  ] || [ "$2" == "1"  ]; then echo ""; fi
	fi
}


# eraseTmp [<file_pattern>]
# erase temporary files with name starting with argument received and ending with .mte.tmp
# if no argument is received, will erase all temporary files.
#
function eraseTmp {
	if [ -d ./mte_Tmp_dir ] && [ "$1" == "" ]; then
		rm -rf  ./mte_Tmp_dir
	fi
	if [ "$1" != "" ]; then
		FILE_PATTERN="_$1_*.mte.tmp"
		if [ $(ls ./mte_Tmp_dir/${FILE_PATTERN} | wc -w) -gt 0 ]; then
			rm ./mte_Tmp_dir/$FILE_PATTERN
		fi
	fi
}

#
#
#
#
function createTplFile {

	# ToDo Perro...
	#  >> ./mte_Tmp_dir/mteTpl.mte
        # echo not implemented yet...
	cp ./mteTpl.mte ./mte_Tmp_dir
}

#
#  ==============================================================================================
#
#                                 A R G U M E N T S   C H E C K
#
#  ==============================================================================================
#



# Check for at least 3 args received
#
if [ "$#" -lt "3" ] && [ "$#" != "1" ]; then
	finish help "Too few arguments($# of 3)..."
fi
if [ "$#" != "1" ]; then
	while [ $# -gt 0 ]; do
		value=$1
		PAR=${value%=*}
		PARVALUE=${value#*=}

		case "$PAR" in
			-v | --verbose)
				VERBOSE=1
				;;
			-i | --autoincremental)
				INCREMENTAL=1
				;;
			-u | --user)
				DBUSER="-u $PARVALUE"
				;;
			-p | --passwd)
				DBPASS="--password=$PARVALUE"
				;;
			-d | --database)
				DBNAME=$PARVALUE
				;;
			-h | --host)
				DBHOST="-h $PARVALUE"
				;;
			-P | --port)
				DBHOSTPORT="-P$PARVALUE"
				;;
			-o | --output)
				CLSPATH=$PARVALUE
				;;
			-h | --help)
				finish "help"
				;;
			-t | --template)
				TPLFILE="$PARVALUE"
				;;
			-k | --package)
				PACKAGE="$PARVALUE"
				;;
			-a | --author)
				AUTHOR="$PARVALUE"
				;;
			-r | --url)
				URL="$PARVALUE"
				;;
			-l | --license)
				LICENSE="$PARVALUE"
				;;
			--ver)
				VERSION="1.0"
				;;
			*)
				finish help "You have specified an invalid argument!"
		esac
		shift
	done
else
	value=$1
	PAR=${value%=*}
	PARVALUE=${value#*=}
	if [ "$PAR" != "-x" ] && [ "$PAR" != "--export" ]; then
		finish help "You have specified an invalid argument!"
	fi
	if [ "$PARVALUE" == "" ] || [ "$PARVALUE" == "-x" ]; then
		TPLFILE="./mteTpl.mte"
	else
		TPLFILE="$PARVALUE"
	fi
	eraseTmp
	mkdir ./mte_Tmp_dir
	if [ "$?" -gt "0" ]; then
		finish help "You don't have writing privileges on this folder... Unable to continue!"
	fi
	createTplFile
	cp ./mte_Tmp_dir/mteTpl.mte $TPLFILE
	finish "Motte Class Template succesfully created: $TPLFILE"
fi

if [ "$DBUSER" == "" ] || [ "$DBNAME" == "" ]; then
	finish help "Missing DB connection info(User/Database). Unable to continue!"
fi

if [ "$DBPASS" == "" ]; then
	DBPASS="-p"
fi

if [ "$CLSPATH" != "." ]; then
	if [ ! -d "$CLSPATH" ]; then
		finish help "Specified output directory don't exist! ($CLSPATH)"
	fi
fi

#
# Testing temporary directory and directory privileges
#

eraseTmp

mkdir ./mte_Tmp_dir
if [ "$?" -gt "0" ]; then
	finish help "You don't have writing privileges on this folder... Unable to continue!"
fi

# Verify if template file exists.
#
if [ "$TPLFILE" == "" ]; then
	message "No template file specified, usig Motte default class schema..." 1
	createTplFile
	TPLFILE="./mte_Tmp_dir/mteTpl.mte"
fi
if [ ! -f $TPLFILE ]; then
	finish help "Missing template file. The file you specified cannot be found($TPLFILE). Unable to continue!"
fi

#
#  ==============================================================================================
#
#                         S T A R T S   D A T A B A S E   P A R S I N G
#
#  ==============================================================================================
#

# Create temporary file with database tables
mysql -N $DBUSER $DBPASS $DBHOST $DBHOSTPORT $DBNAME --execute="SHOW TABLES">./mte_Tmp_dir/_tmp_tables.mte.tmp
if [ "$?" -gt "0" ]; then
	finish "Errors trying to connect with server\nUsing: $DBUSER $DBPASS $DBHOST $DBHOSTPORT $DBNAME"
fi

# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
#        GENERATES TMP FILE FOR STRUCTURE - MUST BE DONE BEFORE PROCESSING TABLE CONTENTS
# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
message "Creating temporary files..."

for TABLE in $(cat ./mte_Tmp_dir/_tmp_tables.mte.tmp); do
	# Create temporary files for Motte(php) code for eache class
	touch ./mte_Tmp_dir/_${TABLE}_INI.mte.tmp
	touch ./mte_Tmp_dir/_${TABLE}_INI_COM.mte.tmp
	touch ./mte_Tmp_dir/_${TABLE}_INCLUDE.mte.tmp
	touch ./mte_Tmp_dir/_${TABLE}_CTR.mte.tmp
	touch ./mte_Tmp_dir/_${TABLE}_CTRI.mte.tmp
	touch ./mte_Tmp_dir/_${TABLE}_SERIAL.mte.tmp
	touch ./mte_Tmp_dir/_${TABLE}_TBLSTRUCT.mte.tmp
	touch ./mte_Tmp_dir/_${TABLE}_LSKEY.mte.tmp
	touch ./mte_Tmp_dir/_${TABLE}_LSLIS.mte.tmp	
done

# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
#             GENERATES CLASS CONTENT BASED ON TABLE STRUCTURE
# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
message "Obtaining table structure from server..."

for TABLE in $(cat ./mte_Tmp_dir/_tmp_tables.mte.tmp); do

	if [ -f ${CLSPATH}/${TABLE}.model.php ]; then
		CLASS_CUSTOMIZED=$(echo $(grep @Customized ${CLSPATH}/${TABLE}.model.php | cut -f3 -d' '))
	else
		CLASS_CUSTOMIZED="false"
	fi

	if [ "$CLASS_CUSTOMIZED" == "false" ]; then
        # create temporary files based on table structure

		message "   Reading table structure for (${TABLE}) from server..."

		mysql -N $DBUSER $DBPASS $DBHOST $DBHOSTPORT $DBNAME --execute="DESCRIBE ${TABLE}" | sed s/"\t"/":"/g > ./mte_Tmp_dir/_tmp_fields.mte.tmp
		if [ "$?" -gt "0" ]; then
			finish "Error when trying to connect to DB\nUsing: $DBUSER $DBPASS $DBHOST $DBHOSTPORT $DBNAME"
		fi

		message "      Processing fields from table: ${TABLE}"

		TBLSTRUCT="\n*\tTable Structure: ${TABLE}\n*\n";

		for LINE in $(cat ./mte_Tmp_dir/_tmp_fields.mte.tmp); do

			# Get field name
			FIELD=$(echo "${LINE}"|cut -d: -f1)

			# Get field type
			TIPO=$(echo "${LINE}"|cut -d: -f2|cut -d'(' -f1)

			# Set 1 if field is table Primary key (Should be named as table with "_id" sufix)
			FIELD_KEY=0
			if [ "${FIELD}" == "${TABLE}_id" ]; then
				FIELD_KEY=1
			fi

			# Set 1 if field is required (NOT NULL/YES)
			FIELD_CTR=0
			if [ "$(echo ${LINE}|cut -d: -f3)" != "YES" ]; then
				FIELD_CTR=1
			fi

			# Getting default value from table structure
			FIELD_DEFAULT=$(echo "${LINE}"|cut -d: -f5)

			# Set 1 if Field is a foreign key (Foreing keys should be named as referenced table name and "_id" sufix)
			FIELD_FK=0
			FIELD_FK_ID=""
			FIELD_FK_TABLE=""
			AUX_CAN=$(echo $(expr $(echo ${FIELD}|sed "s/_/\n/g"|wc -l) - 1))
			if [ "${AUX_CAN}" == "1" ]; then
				FIELD_FK_ID=$(echo ${FIELD}|cut -d_ --fields=1-$(expr $(echo ${FIELD}|sed "s/_/\n/g"|wc -l) - 1))
				FIELD_FK_TABLE=${FIELD_FK_ID}
			fi

			if [ "$(echo ${FIELD}|grep _id|wc -w)" == "1" ] && [ "${FIELD_FK_TABLE}" != "${TABLE}" ]; then
				FIELD_FK=1
			fi

			# list fields
			echo "${FIELD}, ">>./mte_Tmp_dir/_${TABLE}_LSLIS.mte.tmp

			# Generating Motte(php) code based on field type and expected behavior
			if [ "${FIELD_KEY}" == "1" ]; then
				echo "${FIELD}, ">>./mte_Tmp_dir/_${TABLE}_LSKEY.mte.tmp
				if [ "$INCREMENTAL" != "1" ]; then
					# Auto_incremental / Uses a "serial" table for autonumeric values on InnoDB
			   		echo "include_once(MTE_MODEL.'\/serial.model.php');\n">>./mte_Tmp_dir/_${TABLE}_INCLUDE.mte.tmp
					echo "\n\t\t\/\/ Load autoincremental value\n\t\tif (\$this->countErrorExec() == 0){\n\t\t\t\/\/ Ask for autoincremental value\n\t\t\t\$tblSerial = new tbl_serial(\$this->getEngine());\n\t\t\t\$record['${FIELD}'] = \$tblSerial->getNextValue('${FIELD}');\n\t\t}\n">>./mte_Tmp_dir/_${TABLE}_SERIAL.mte.tmp
				fi
			else
				if [ "${FIELD_FK}" == "1" ]; then
					# Add include_once based on foreign fields
					echo "include_once(MTE_MODEL.'\/$(echo ${FIELD}|cut -d_ -f1).model.php');\t\/\/ Foreign field\n">>./mte_Tmp_dir/_${TABLE}_INCLUDE.mte.tmp

					# Initialize foreign fields with corresponding table default value
					echo "\n\t\t\/\/ Initialize foreign fields ${FIELD}\n\t\t\$tbl${FIELD_FK_TABLE} = new tbl_${FIELD_FK_TABLE}(\$this->getEngine());\n\t\t\$record['${FIELD}'] = \$tbl$tbl${FIELD_FK_TABLE}->getDefaultId();\n\n">>./mte_Tmp_dir/_${TABLE}_INI.mte.tmp

					# Record check for needed values (NOT NULL Fields)
					echo "\n\t\t\/\/ ${FIELD}\n\t\t\$tbl${FIELD_FK_TABLE} = new tbl_${FIELD_FK_TABLE}(\$this->getEngine());\n\t\tif (!\$tbl${FIELD_FK_TABLE}->exists(\"${FIELD_FK_ID}_id='\".\$record['${FIELD}'].\"'\")){\n\t\t\t\$this->addErrorExec(\__('No data could be found for field').' ${FIELD} ');\n\t\t}\n">>./mte_Tmp_dir/_${TABLE}_CTR.mte.tmp

					# Integrity check control for record deletion based on foreign key on other tables
					echo "\n\t\t\/\/ Integrity for ${TABLE}\n\t\t\$tbl${TABLE} = new tbl_${TABLE}(\$this->getEngine());\n\t\tif (\$tbl${TABLE}->exists(\"${FIELD}='\".\$record['${FIELD}'].\"'\")){\n\t\t\t\$this->addErrorExec(\$tbl${TABLE}->getTableComment());\n\t\t}\n">>./mte_Tmp_dir/_${FIELD_FK_TABLE}_CTRI.mte.tmp

					# Add include_once for tables used at Integrity check on record deletion.
					echo "include_once(MTE_MODEL.'\/${TABLE}.model.php');\t\/\/ Integrity Check\n">>./mte_Tmp_dir/_${FIELD_FK_TABLE}_INCLUDE.mte.tmp

				else
					# Inicializing class attributes with default values
					if [ "${FIELD_DEFAULT}" != "" ] && [ "${FIELD_DEFAULT}" != "NULL" ]; then
						VALUE="\"$(echo ${FIELD_DEFAULT} | sed "s/\\//\\\\\//g" )\"";
					else
						case "$(echo ${TIPO} | tr 'A-Z' 'a-z' )" in
							numeric | bit | int | decimal | tinyint | smallint | mediumint | integer | bigint | real | double | float | decimal)
								VALUE='0'
								;;
							varchar | char | text | tinytext | mediumtext | longtext)
								VALUE='\"\"'
								;;
							datetime | timestamp)
								VALUE="date('Y-m-d H:i:s')"
								;;
							date)
								VALUE="date('Y-m-d')"
								;;
							time)
								VALUE="date('H:i:s')"
								;;
							year)
								VALUE="date('Y')"
								;;
						esac
					fi

					echo "${FIELD}=${VALUE}">>./mte_Tmp_dir/_${TABLE}_INI_COM.mte.tmp

					# Control de datos
					VALUE=''
					if [ "${FIELD_CTR}" == "1" ]; then
						COMMON_FIELD="\n\t\t\/\/ ${FIELD}\n\t\tif ("
						COMMON_FIELD2="\$record['${FIELD}']"
						COMMON_FIELD3=" == ''){\n\t\t\t\$this->addErrorExec(\__('Field').' "
						COMMON_FIELD4="){\n\t\t\t\$this->addErrorExec(\__('Field').' "
						# based on field type
						case "$(echo ${TIPO} | tr 'A-Z' 'a-z' )" in
							int)
								VALUE="$COMMON_FIELD !is_numeric($COMMON_FIELD2) ${COMMON_FIELD4} ${FIELD} '.\__('must receive an integer value.'));\n\t\t}"
								;;
	                                                decimal)
								VALUE="$COMMON_FIELD !is_numeric($COMMON_FIELD2) ${COMMON_FIELD4} ${FIELD}  '.\__('must receive a numeric value.'));\n\t\t}"
								;;
							varchar | char)
								VALUE="$COMMON_FIELD $COMMON_FIELD2 $COMMON_FIELD3 ${FIELD} '.\__('cannot be empty.'));\n\t\t}"
								;;
						esac
						echo "${VALUE}">>./mte_Tmp_dir/_${TABLE}_CTR.mte.tmp
					fi
				fi
			fi

			message "         Field ${LINE}..."
		done

		# Var assignment alingment
		if [ "$(cat ./mte_Tmp_dir/_${TABLE}_INI_COM.mte.tmp | wc -l)" -gt "0" ]; then
			echo "\n\t\t\/\/ Initialize others fields\n">>./mte_Tmp_dir/_${TABLE}_INI.mte.tmp

			# Max length
			MAX_LENGTH=0
			for LINE in $(cat ./mte_Tmp_dir/_${TABLE}_INI_COM.mte.tmp); do
				LINE_LENGTH=$(echo ${LINE} | cut -d= -f1 | wc -m)
				if [ ${LINE_LENGTH} -gt ${MAX_LENGTH} ]; then
					MAX_LENGTH=${LINE_LENGTH}
				fi
			done
			MAX_LENGTH=$(expr ${MAX_LENGTH} + 1)

			for LINE in $(cat ./mte_Tmp_dir/_${TABLE}_INI_COM.mte.tmp); do
				LINE_LENGTH=$(echo ${LINE} | cut -d= -f1 | wc -m)
				BEFORE="\t\t\$record['$(echo ${LINE} | cut -d= -f1)']"
				AFTER="$(echo ${LINE} | cut -d= -f2);\n"
				SPACES=$(echo '################################' | cut -c 1-$(expr ${MAX_LENGTH} - ${LINE_LENGTH}))
				echo "${BEFORE}${SPACES}= ${AFTER}"|sed s/'#'/'\\ '/g>>./mte_Tmp_dir/_${TABLE}_INI.mte.tmp
			done
		fi

		# Add comment line for Class
		MAX_LENGTH='0:0:0:0:0:0'
		echo "field:type:null:key:default:extra">./mte_Tmp_dir/_tmp_fieldsc.mte.tmp
		cat ./mte_Tmp_dir/_tmp_fields.mte.tmp>>./mte_Tmp_dir/_tmp_fieldsc.mte.tmp
		for LINE in $(cat ./mte_Tmp_dir/_tmp_fieldsc.mte.tmp); do
			COUNTER=1
			LENAUX=''
			while [  $COUNTER -lt 7 ]; do
				# Maximo actual
				MAX_LENGTH_AUX=$(echo ${MAX_LENGTH} | cut -d: -f${COUNTER})
				declare -i MAX_LENGTH_AUX
				LINE_LENGTH=$(echo ${LINE} | cut -d: -f${COUNTER} | wc -m)
				LINE_LENGTH=$(expr ${LINE_LENGTH} + 1)
				if [ ${LINE_LENGTH} -gt ${MAX_LENGTH_AUX} ]; then
					LENAUX=${LENAUX}${LINE_LENGTH}':'
				else
					LENAUX=${LENAUX}${MAX_LENGTH_AUX}':'
				fi
				COUNTER=$(expr ${COUNTER} + 1)
			done
			MAX_LENGTH=${LENAUX}
		done


		echo "\n*\tTable structure: ${TABLE}\n*\n">>./mte_Tmp_dir/_${TABLE}_TBLSTRUCT.mte.tmp
		HEADER=''
		COUNTER=1
	        while [  $COUNTER -lt 7 ]; do
			MAX_LENGTH_AUX=$(echo ${MAX_LENGTH} | cut -d: -f${COUNTER})
			SPACES=$(echo '----------------------------------------------------------------------------' | cut -c 1-${MAX_LENGTH_AUX})
			HEADER="${HEADER}${SPACES}+"
			COUNTER=$(expr ${COUNTER} + 1)
		done

		echo "*\t+${HEADER}\n">>./mte_Tmp_dir/_${TABLE}_TBLSTRUCT.mte.tmp
		LINE_NBR=1
		for LINE in $(cat ./mte_Tmp_dir/_tmp_fieldsc.mte.tmp); do
			COUNTER=1
			AUX='#'
	        	while [  $COUNTER -lt 7 ]; do
				LINE_LENGTH=$(echo ${LINE} | cut -d: -f${COUNTER} | wc -m)
				MAX_LENGTH_AUX=$(echo ${MAX_LENGTH} | cut -d: -f${COUNTER})
				BEFORE=$(echo ${LINE} | cut -d: -f${COUNTER})
				SPACES=$(echo '#################################################' | cut -c 1-$(expr ${MAX_LENGTH_AUX} - ${LINE_LENGTH}))
				AUX=${AUX}${BEFORE}${SPACES}"|#"
				COUNTER=$(expr ${COUNTER} + 1)
			done
			echo "*\t|${AUX}\n" | sed s/'#'/'\\ '/g | sed -r s/'\/'/'\\\/'/g >>./mte_Tmp_dir/_${TABLE}_TBLSTRUCT.mte.tmp
			if [ "${LINE_NBR}" == "1" ]; then
				echo "*\t+${HEADER}\n">>./mte_Tmp_dir/_${TABLE}_TBLSTRUCT.mte.tmp
			fi
			LINE_NBR=$(expr ${LINE_NBR} + 1)
		done
		echo "*\t+${HEADER}">>./mte_Tmp_dir/_${TABLE}_TBLSTRUCT.mte.tmp
	else
		message "   Ignoring table (${TABLE}) because \"CUSTOMIZED\" tag is set to TRUE..."
	fi
done


# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
#              GENERATES MOTTE(php) CLASS AND REPLACE TEMPLATE VARIABLES WITH GENERATED CONTENT
# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
message "DB Structure scan completed. Generating Motte Classes..." 2

for TABLE in $(cat ./mte_Tmp_dir/_tmp_tables.mte.tmp); do

        if [ -f ${CLSPATH}/${TABLE}.model.php ]; then
                CLASS_CUSTOMIZED=$(echo $(grep @Customized ${CLSPATH}/${TABLE}.model.php | cut -f3 -d' '))
        else
                CLASS_CUSTOMIZED="false"
        fi

	if [ "$CLASS_CUSTOMIZED" == "false" ]; then
		message "   Motte Class: ${TABLE}.model.php"

		INI=$(cat ./mte_Tmp_dir/_${TABLE}_INI.mte.tmp)
		CTR=$(cat ./mte_Tmp_dir/_${TABLE}_CTR.mte.tmp)
		INCLUDE=$(cat ./mte_Tmp_dir/_${TABLE}_INCLUDE.mte.tmp | uniq)
		SERIAL=$(cat ./mte_Tmp_dir/_${TABLE}_SERIAL.mte.tmp)
		CTRI=$(cat ./mte_Tmp_dir/_${TABLE}_CTRI.mte.tmp)
		TBLSTRUCT=$(cat ./mte_Tmp_dir/_${TABLE}_TBLSTRUCT.mte.tmp)
		LSKEY=$(cat ./mte_Tmp_dir/_${TABLE}_LSKEY.mte.tmp)
		LSLIS=$(cat ./mte_Tmp_dir/_${TABLE}_LSLIS.mte.tmp)


		if [ "$TABLE" == "serial" ]; then
			FUNCSERIAL=$(echo -e "\\\tpublic function getNextValue(\$key){\\\n\\\t\\\t\\\t\$where = new mteWhereSql();\\\n\\\t\\\t\$where->addAND('serial_id','=',\"'\$key'\");\\\n\\\t\\\tif (!\$this->exists(\$where-\>fetch())){\\\n\\\t\\\t\\\tprint(\"Non existent key '\$key' on database.\");\\\n\\\t\\\t\\\texit();\\\n\\\t\\\t}\\\n\\\n\\\t\\\t\$record = \$this->getRecord(\$where->fetch());\\\n\\\t\\\t\$record['codigo'] = \$record['codigo']+1;\\\n\\\n\\\t\\\t\$this->updateRecord(\$record);\\\n\\\n\\\t\\\treturn \$record['codigo'];\\\n\\\t}\\\n")
		else
			FUNCSERIAL=""
		fi


sed -e "s/@DATE/$(date)/g" \
-e "s/@TABLE/$(echo ${TABLE})/g" \
-e "s/@INICIALIZE/$(echo ${INI})/g" \
-e "s/@SERIAL/$(echo ${SERIAL})/g" \
-e "s/@CTRI/$(echo ${CTRI})/g" \
-e "s/@CTR/$(echo ${CTR})/g" \
-e "s/@INCLUDES/$(echo ${INCLUDE})/g" \
-e "s/@TBLSTRUCT/$(echo $TBLSTRUCT)/g" \
-e "s/@AUTHOR/$(echo ${AUTHOR})/g" \
-e "s/@APPURL/$(echo ${URL})/g" \
-e "s/@PACKAGE/$(echo ${PACKAGE})/g" \
-e "s/@LICENSE/$(echo ${LICENSE})/g" \
-e "s/@VERSION/$(echo ${VERSION})/g" \
-e "s/@FIELDKEY/$(echo ${LSKEY})/g" \
-e "s/@FIELDLIST/$(echo ${LSLIS})/g" \
-e "s/@FUNCSERIAL/$(echo ${FUNCSERIAL})/g" $TPLFILE > ./mte_Tmp_dir/${TABLE}.model.tmp

		cat ./mte_Tmp_dir/${TABLE}.model.tmp |sed -r s/^[' ']//g> $CLSPATH/${TABLE}.model.php
	else
	        message "   Ignoring table (${TABLE}) because \"CUSTOMIZED\" tag is set to TRUE..."
	fi
done

message "Erasing temporary files..."

# Erasing tmp files
eraseTmp

message "Task finished." 1
exit 0
