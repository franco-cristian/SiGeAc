# SiGeAc - Sistema de Gestión Académica

![Captura de Pantalla del Login de SiGeAc](https://i.imgur.com/Cz9janO.png)

**SiGeAc** es una aplicación web moderna y robusta, diseñada para la gestión académica en instituciones educativas. Construida con el stack TALL (Tailwind, Alpine, Livewire, Laravel), la plataforma ofrece una experiencia de usuario fluida, segura y completamente responsiva, con un enfoque en la usabilidad y un diseño "dark-mode first".

Este proyecto fue desarrollado para la materia **Programación IV** de la carrera Tecnicatura Universitaria en Programación, en la **Universidad Tecnológica Nacional (UTN) - Facultad Regional Resistencia, Sede Formosa.**

---

## ✨ Características Principales

### Para Administradores (`SuperAdmin`)
- **Dashboard con Estadísticas Clave:** Visualización en tiempo real del total de alumnos, docentes y cursos.
- **Gestión Completa de Usuarios:** CRUD completo para crear, ver, editar y eliminar usuarios.
- **Asignación de Roles:** Capacidad para promover o degradar usuarios entre los roles de Alumno y Docente.
- **Gestión Dinámica de Cursos:** CRUD completo para crear y gestionar la oferta académica, incluyendo materias, comisiones y múltiples bloques horarios.
- **Gestión de Inscripciones:** Panel para definir períodos de inscripción y gestionar listas de espera.

### Para Docentes (`Docente`)
- **Panel Contextual:** Vista de alumnos filtrada por curso y comisión.
- **Herramientas de Gestión:** Búsqueda avanzada de alumnos (nombre, email, DNI, teléfono), paginación personalizable y toggle para mostrar/ocultar fotos.
- **Acciones Rápidas:** Ver el perfil detallado de un alumno o desinscribirlo de un curso con confirmación.
- **Reportes en PDF:** Generación de listas de alumnos personalizadas con selección de columnas y orientación de página.

### Para Alumnos (`Alumno`)
- **Registro Completo y Seguro:** Formulario de registro con validación robusta y subida de foto de perfil obligatoria.
- **Dashboard Personal:** Widget de perfil con información de contacto y un horario semanal interactivo.
- **Inscripción a Cursos:** Interfaz para explorar la oferta académica, filtrarla y inscribirse en cursos, respetando los cupos y períodos de inscripción.
- **Gestión de Inscripciones:** Posibilidad de darse de baja de un curso durante el período habilitado.

---

## 🚀 Tecnologías Utilizadas

![PHP 8.3+](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php)
![Laravel 12](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel)
![Livewire 3](https://img.shields.io/badge/Livewire-3-4d51b3?style=for-the-badge&logo=livewire)
![Alpine.js 3](https://img.shields.io/badge/Alpine.js-3-8BC0D0?style=for-the-badge&logo=alpine.js)
![Tailwind CSS 3](https://img.shields.io/badge/Tailwind_CSS-3-38B2AC?style=for-the-badge&logo=tailwind-css)
![MySQL 8.4](https://img.shields.io/badge/MySQL-8.4-4479A1?style=for-the-badge&logo=mysql)
![Vite 7](https://img.shields.io/badge/Vite-7-646CFF?style=for-the-badge&logo=vite)

---

## 🔧 Instalación en un Entorno Local

Para clonar y ejecutar este proyecto sigue estos pasos.

### Prerrequisitos
- Git
- PHP 8.3+
- Composer
- Node.js & NPM
- Un servidor MySQL

### Pasos de Instalación

1.  **Clonar el repositorio:**
    ```bash
    git clone https://github.com/franco-cristian/SiGeAc.git
    cd SiGeAc
    ```

2.  **Instalar dependencias:**
    ```bash
    composer install
    npm install
    ```

3.  **Configurar el entorno:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    A continuación, abre el archivo `.env` y configura tus credenciales de base de datos (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

4.  **Preparar la base de datos:**
    Este comando creará todas las tablas y las llenará con datos de prueba (roles, admin, docentes, materias y cursos).
    ```bash
    php artisan migrate:fresh --seed
    ```

5.  **Crear el enlace simbólico:**
    Necesario para que las fotos de perfil sean visibles.
    ```bash
    php artisan storage:link
    ```

6.  **Compilar los assets y ejecutar:**
    ```bash
    # En una terminal
    npm run dev

    # En otra terminal
    php artisan serve
    ```

7.  **¡Listo!** Accede a la aplicación en `http://127.0.0.1:8000`.

### Credenciales de Acceso de Prueba
-   **SuperAdmin:** `admin@sga.com` / `password`
-   **Docente:** `crozy.german@sga.com` / `password`
-   **Alumno:** Regístrate como un nuevo usuario o usa uno de los creados por los seeders.

---

## 👨‍💻 Autor

-   **Cristian Franco**
-   **Portfolio:** [franco-cristian.github.io](https://franco-cristian.github.io/)
-   **GitHub:** [@franco-cristian](https://github.com/franco-cristian)
-   **LinkedIn:** [linkedin.com/in/cristian-ricardo-franco](https://www.linkedin.com/in/cristian-ricardo-franco/)