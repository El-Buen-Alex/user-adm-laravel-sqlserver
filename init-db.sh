#!/bin/bash

while true; do
    /opt/mssql-tools/bin/sqlcmd -S sqlsrv -U $MSSQL_USER -P $MSSQL_SA_PASSWORD -Q "SELECT 1" -t 5 > /dev/null 2>&1
    if [ $? -eq 0 ]; then
        echo "SQL Server está disponible"
        echo "IF NOT EXISTS (SELECT * FROM master.sys.databases WHERE name = '$MSSQL_DB_NAME') BEGIN EXEC('CREATE DATABASE $MSSQL_DB_NAME'); END" > /tmp/init-db.sql
        /opt/mssql-tools/bin/sqlcmd -S sqlsrv -U $MSSQL_USER -P $MSSQL_SA_PASSWORD -i /tmp/init-db.sql
        echo "SCHEMA CREADO"
        echo "Ejecutando migraciones y seeders de Laravel..."
        cd /var/www/html
        php artisan migrate
        php artisan db:seed
        echo "Migraciones y seeders ejecutados"
        break
    else
        echo "Esperando por SQL Server..."
    fi
done

exec apache2-foreground
tail -f /dev/null