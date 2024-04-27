
# Prueba 2024

API RESTful en PHP que facilita la gestión de usuarios y transacciones financieras entre ellos. Implementa una arquitectura limpia y se despliega fácilmente mediante Docker, ofreciendo endpoints para el registro de usuarios y la ejecución de transacciones financieras.

## Prerrequisitos

Antes de comenzar, asegúrate de tener instalado Docker y Docker Compose en tu sistema para manejar los contenedores y dependencias del proyecto.

## Configuración del Proyecto

Instrucciones paso a paso para configurar el proyecto localmente.

### Clonar el Repositorio

Clona este repositorio en tu máquina local usando:

```bash
git clone [URL del repositorio]
cd [nombre del directorio del proyecto]
```

### Reemplazar archivo .env

Renombra el archivo .env.example a .env y reemplaza los valores de las variables de entorno con los valores adecuados para tu entorno de desarrollo.
```bash
cp .env.example .env
```

## Configuración de Docker

Para configurar y ejecutar el entorno del proyecto con Docker, sigue estos pasos:

- Construye los contenedores de Docker y ejecútalos:

```bash
docker-compose up --build
```

## Uso

Este proyecto define dos endpoints principales para interactuar con la aplicación:

### Registrar un Usuario

Para registrar un nuevo usuario, envía una solicitud POST a `/register` con el siguiente payload JSON:

```bash
{
  "fullName": "John Doe",
  "documentId": "123456789",
  "email": "example@example.com",
  "password": "password123",
  "accountType": "common",
  "amount": 100.00 // Opcional
}
```

### Realizar una Transacción

Para realizar una transacción, envía una solicitud POST a `/transaction` con el siguiente payload JSON:

```bash
{
  "payerId": "4",
  "payeeId": "5",
  "amount": 100.00
}
```

### Acceso a la Aplicación

Para probar la funcionalidad de la aplicación, se recomienda utilizar Postman o cualquier otro cliente de API. No es necesario un navegador web ya que se trata de una API RESTful. Puedes enviar solicitudes HTTP a los endpoints proporcionados para registrar usuarios y realizar transacciones financieras:

- __POST /register:__ Para registrar nuevos usuarios.
- __POST /transaction:__ Para ejecutar transacciones entre usuarios.

La aplicación está configurada para escuchar en http://localhost:8000. Asegúrate de configurar adecuadamente Postman o tu cliente de API para apuntar a esta dirección cuando realices las solicitudes.

### Test Unitarios

Para ejecutar los test unitarios, utiliza el siguiente comando:

```bash
composer run-tests
```