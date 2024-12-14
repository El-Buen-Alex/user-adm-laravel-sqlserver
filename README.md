### BACKEND ADMINISTRACIÓN USUARIOS
 Hola, acontinuación te menciono los pasos que debes seguir para ejecutar este proyecto correctamente:
- El entorno está pensado para un despliegue rapido (dependiendo de la descarga de las imagenes), para ello es necesario contar con docker.
- Segundo, configurar el archivo ENV.
- Una vez se tenga docker, ejecutar "**docker compose up -d**" en la raiz del proyecto, esto automaticamente levantara un servicio de base de datos en sqlserver y una vez que el servicio este disponible, para reducir tiempo, se creará automaticamente la base de datos(incluyendo migraciones y seeders declarados en laravel).
- Listo, el proyecto se estará ejecutando.
