# Sistema de administración para un refugio de mascotas

![demo](./public/README/record_231225_212036.gif)

Este es un proyecto hecho con:

* [Laravel 10.25.2](https://laravel.com/docs/10.x/readme)
* [Laravel-AdminLTE 3.9.2](https://github.com/jeroennoten/Laravel-AdminLTE)

Lo que hace este sistema es: dar de alta a las mascotas, solicitar una adopción y deliberar la adopcion de las mascotas.

También permite: solicitar devoluciones de mascotas, deliberar las solicitudes de devolución y marcar la acción de recogida y devolución de las mascotas al refugio.

Por último, el sistema tiene una sección donde se puede ver, por cada mascota su linea de tiempo. También por cada adoptante se puede ver su linea de tiempo.

Al deliberar la adopción o devolución de la mascota se le notifica al adoptante mediante email. 

Para mandar un email yo usé un [*Laravel Markdown Mailable*](https://laravel.com/docs/10.x/mail#generating-markdown-mailables), que después [personalicé](https://laravel.com/docs/10.x/mail#customizing-the-components).

## Ejemplo de uso para adoptar una mascota

Ir a la sección *Request adoption* y seleccionar de la lista a una mascota, después dar click en el botón de acción (columna *Actions*) para que el sistema pida la información del adoptante. El resultado al llenar el formulario es una solicitud de adopción.

![demo](./public/README/demo_request_adoption.png)

![demo](./public/README/demo_request_adoption_form_2.png)

## Ejemplo de deliberar una adopción

Ir a la sección *Deliberate ADOPTION requests* y seleccionar el registro que se quiere deliberar.

Al usar cualquiera de los botones de acción pedirá agregar un mensaje que se le enviará al adoptante mediante email.

![demo](./public/README/demo_deliberate_requests.png)

![demo](./public/README/demo_deliberate_requests_message.png)

![demo](./public/README/demo_deliberate_requests_email.png)

## Ejemplo de marcar a una mascota como recogida

Ir a la sección *Mark picked up pets* y usar el botón de acción cuando la mascota fue recogida.

Al usar el botón de acción el sistema pedirá agregar una nota, se debe colocar obligatoriamente una observación.

![demo](./public/README/demo_mark_pets_picked_up.png)

![demo](./public/README/demo_mark_pets_picked_up_note.png)

## Ver la linea de tiempo de una mascota

Ir a la sección *Pet and Adopter Timeline* y en la tabla de mascotas o de adoptantes seleccionar un registro, después dar click en el botón de acción.

![demo](./public/README/record_231226_232411.gif)

# Obtener y usar el código fuente (para desarrolladores)

Las instrucciones mostradas a continuación serán enfocadas a [Laragon](https://laragon.org/), que es lo que yo uso y recomiendo. Laragon es para sistemas Windows.

## Obtener Laragon y PHP 8

Ir a la [sección de descargas](https://laragon.org/download/index.html) de Laragon, descargarlo y después descomprimirlo, las versiones *full* y *portable* funcionan bien. En este caso se hacen los pasos para la versión portable.

![demo](./public/README/laragon_portable_descargar.png)

Para usar Laravel v10 se necesita PHP v8, para obtenerlo se debe ir a [php.net/downloads](https://www.php.net/downloads) y dar click en los descargables para windows.

![demo](./public/README/php_downloads.png)

## Cambiar la versión de PHP de Laragon

En mi caso la versión de PHP 8.3.1 ***Non Thread Safe (nts)*** me funciona bien. Se debe descargar el `.zip` en la carpeta `laragon/bin/php` y después descomprimirlo.

![demo](./public/README/php_downloads_nts.png)

Abrir Laragon

![demo](./public/README/abrir_laragon.png)

Al abrir Laragon dar click derecho en cualquier parte de la ventata de Laragon y después:

`PHP -> Version -> php-8.3.1`

![demo](./public/README/laragon_cambiar_version_php.png)

## Descargar este proyecto

Abrir la terminal de Laragon.

Posicionar la terminal en la carpeta `www` de Laragon.

Clonar el proyecto con `git clone`.

![demo](./public/README/clonar_repo.png)

Con la terminal hacer `cd mascotas`

## Actualizar composer

Actualizar composer con `composer self-update`

**Puede haber errores al actualizar de tipo:** 

```
Composer update failed: composer.phar could not be written.
```

Como se muestra en el siguiente [hilo de GitHub](https://github.com/composer/composer/issues/10444). 

La solución recomendada es: **Cambiar la [versión de PHP](https://windows.php.net/download#php-8.3) de Laragon** hasta encontrar una versión que no muestre errores. Se sugiere que la versión de PHP a la que se cambie sea `>=8.1`.

## *Levantar* el proyecto clonado

Para evitar redundancias, escribí una entrada en mi blog sobre este tema. 

La entrada de blog se encuentra [aquí](https://juanmx.github.io/2022/05/21/apuntes-laravel-fullstack.html#trabajar-en-laragon-con-proyectos-de-laravel).

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

AdminLTE is an open source project that is licensed under the [MIT license](https://adminlte.io/docs/3.1//license.html)
