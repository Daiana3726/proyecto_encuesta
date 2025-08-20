README - Sistema de Encuestas Web
¿Qué hace el proyecto?
Una encuesta web donde los usuarios pueden calificar un servicio, seleccionar lo que más les gustó y dejar comentarios.
Archivos principales:

index.html - El formulario de encuesta
encuesta.php - Procesa las respuestas y las guarda
encuesta.css - Los estilos

Cómo funciona:

Usuario llena la encuesta (calificación con estrellas, gustos con checkboxes, comentarios)
PHP recibe los datos del formulario
Se conecta a la base de datos MySQL
Guarda las respuestas en la tabla encuestas
Muestra página de agradecimiento con resumen

Base de datos:

Nombre: registro_usuarios (misma que reservas)
Tabla: encuestas
Campos: rating, gustos, comentarios, fecha

Tipos de datos que maneja:

Rating: Calificación de 1-5 estrellas
Gustos: Checkboxes múltiples (calidad, precio, atención, etc.)
Comentarios: Texto libre opcional

Lo más importante que hice:

Procesamiento de diferentes tipos de inputs (radio, checkbox, textarea)
Conversión de arrays a texto para la base de datos
Página de confirmación HTML completa
Validación y manejo de datos opcionales

Características técnicas:

Prepared statements para seguridad
Manejo de checkboxes múltiples
Generación de HTML dinámico como respuesta
Timestamps automáticos con NOW()
