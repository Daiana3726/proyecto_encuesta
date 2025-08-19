<?php
// Credenciales para conectar a MySQL (mismo servidor que reservas)
$servername = "localhost";         // Servidor local (XAMPP)
$username = "root";               // Usuario por defecto
$password = "";                   // Sin contraseña en XAMPP
$dbname = "registro_usuarios";    // Misma BD que usamos para reservas

// Crear la conexión usando MySQLi
// $conn = objeto que maneja la comunicación con MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión falló
if ($conn->connect_error) {
    // die() = detener todo si no hay conexión (sin BD no funciona nada)
    die("Conexión fallida: " . $conn->connect_error);
}

// --- 2. VERIFICAR QUE SE ENVIARON DATOS ---
// $_SERVER["REQUEST_METHOD"] = método usado para enviar datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- 3. RECIBIR Y PROCESAR DATOS DEL FORMULARIO ---
    // isset() = ¿existe esta variable? Evita errores si no seleccionó nada
    // ? : = operador ternario (if corto): condición ? si_verdadero : si_falso
    $rating = isset($_POST['rating']) ? $_POST['rating'] : 'No seleccionado';
    
    // GUSTOS (checkboxes) - Se pueden seleccionar MÚLTIPLES opciones
    // Los checkboxes llegan como array: gustos[] = ['calidad', 'precio']
    // Si no marcó ninguno, asignar array vacío [] para evitar errores
    $gustos = isset($_POST['gustos']) ? $_POST['gustos'] : [];
    
    // Convertir array de gustos en texto separado por comas
    // !empty($gustos) = ¿el array NO está vacío?
    // implode(", ", $array) = convierte ['a', 'b', 'c'] en "a, b, c"
    $gustos_texto = !empty($gustos) ? implode(", ", $gustos) : 'Ninguno seleccionado';
    
    // COMENTARIOS (textarea) - Texto libre
    // trim() = quitar espacios al inicio y final
    // Puede estar vacío, por eso ponemos mensaje por defecto
    $comments = isset($_POST['comments']) ? trim($_POST['comments']) : 'Sin comentarios';
    
    // --- 4. CREAR PÁGINA HTML COMPLETA COMO RESPUESTA ---
    // En lugar de mostrar solo texto, creamos una página HTML bonita
    // echo = imprimir/mostrar contenido al navegador
    echo "<!DOCTYPE html>";
    echo "<html lang='es'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>"; 
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>"; 
    echo "<title>Gracias por tu opinión</title>";
    echo "<link rel='stylesheet' href='encuesta.css'>";  // Usar los mismos estilos
    echo "</head>";
    echo "<body>";
    echo "<div class='survey-container'>";  // Mismo contenedor que el formulario
    echo "<div class='result-container'>";
    
    // Mostrar título de agradecimiento
    echo "<h2>¡Gracias por tu opinión! 🎉</h2>";
    
    // Mostrar calificación con íconos
    echo "<div class='result-item'>";
    echo "<h3>📊 Tu calificación:</h3>";
    // Concatenar variables con texto usando el operador .
    echo "<p><strong>" . $rating . "</strong> estrella(s)</p>";
    echo "</div>";
    
    // Mostrar gustos seleccionados
    echo "<div class='result-item'>";
    echo "<h3>❤️ Lo que más te gustó:</h3>";
    echo "<p>" . $gustos_texto . "</p>";
    echo "</div>";
    
    // Mostrar comentarios
    echo "<div class='result-item'>";
    echo "<h3>💬 Tus comentarios:</h3>";
    // Operador ternario dentro del echo: si hay comentarios, mostrarlos; si no, mensaje
    echo "<p>" . ($comments ? $comments : 'Sin comentarios adicionales') . "</p>";
    echo "</div>";
    
    // --- 5. INSERTAR EN BASE DE DATOS ---
    // Prepared statement para guardar de forma segura
    // NOW() = función de MySQL que inserta la fecha/hora actual automáticamente
    $sql = "INSERT INTO encuesta (rating, gustos, comentarios, fecha) VALUES (?, ?, ?, NOW())";
    
    // prepare() = preparar la consulta con placeholders (?)
    $stmt = $conn->prepare($sql);
    
    // bind_param() = vincular variables a los placeholders
    // "sss" = 3 strings (rating, gustos_texto, comments)
    $stmt->bind_param("sss", $rating, $gustos_texto, $comments);
    
    // execute() = ejecutar la consulta preparada
    // Devuelve true si se insertó correctamente, false si hubo error
    if ($stmt->execute()) {
        // Mensaje de éxito con estilo verde
        echo "<p style='color: green;'>✅ Datos guardados correctamente en la base de datos.</p>";
    } else {
        // Mensaje de error con estilo rojo
        // $conn->error = mensaje específico del error de MySQL
        echo "<p style='color: red;'>❌ Error al guardar: " . $conn->error . "</p>";
    }
    
    // close() = cerrar el prepared statement para liberar memoria
    $stmt->close();
    
    // Enlace para volver al formulario
    echo "<div class='back-link'>";
    echo "<a href='index.html'>← Volver a la encuesta</a>";
    echo "</div>";
    
    // Cerrar los divs del HTML
    echo "</div>";  // Cierra result-container
    echo "</div>";  // Cierra survey-container
    echo "</body>";
    echo "</html>";
    
} else {
    // --- 6. ACCESO DIRECTO SIN FORMULARIO ---
    // Si alguien escribe directamente "encuesta.php" en el navegador
    // sin haber llenado el formulario, mostrar error
    echo "<h2>Acceso no válido</h2>";
    echo "<p>Por favor, completa la encuesta primero.</p>";
    echo "<a href='index.html'>Ir a la encuesta</a>";
}

// --- 7. CERRAR CONEXIÓN ---
// close() = cerrar la conexión con MySQL
// Libera recursos del servidor, buena práctica
$conn->close();
?>