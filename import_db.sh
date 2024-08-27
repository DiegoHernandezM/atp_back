#!/bin/bash

# Variables de entorno
DB_HOST="db"
DB_USER="root"
DB_PASSWORD="secret"
DB_NAME="quizz"
DUMP_FILE="quizz.sql"

# Importa el dump
mysql -h $DB_HOST -u $DB_USER -p$DB_PASSWORD $DB_NAME < $DUMP_FILE
