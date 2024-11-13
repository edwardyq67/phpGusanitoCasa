<?php

// Verificar si 'cliente_id' está en la URL o se pasa como valor predeterminado (por ejemplo, 11)
$cliente_id = isset($_GET['cliente_id']) && is_numeric($_GET['cliente_id']) ? $_GET['cliente_id'] : 11;

// Mostrar el cliente_id para depuración
echo "ID de cliente: " . $cliente_id;

// Consulta para obtener los datos del cliente
$sql = "SELECT * FROM cliente WHERE id = :cliente_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
$stmt->execute();
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($cliente) {
    $nombre = $cliente['nombre'];
    $apellido = $cliente['apellido'];
    $telefono = $cliente['cel'];
    $correo = $cliente['correo'];
} else {
    echo "Cliente no encontrado.";
}
?>

<div class="bg-transparent w-[80%]  rounded-md p-4">
    <button class="py-1 px-3 rounded-t-md bg-blue-600 text-white hover:bg-blue-500 transition-all duration-200">Editar Pedido</button>
    <form method="POST" action="update_cliente.php" class="bg-white h-full p-4">
        <!-- Campo hidden para el clienteId -->
        <input id="cliente-id" name="cliente_id" type="hidden" value="">

        <div class="rounded-b-md rounded-tr-md text-sm grid grid-cols-5 gap-4 h-auto">
            <div class="flex flex-col col-span-2">
                <label for="nombre" class="font-bold mb-1">Nombre: </label>
                <input id="nombre" name="nombre" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" value="<?php echo htmlspecialchars($nombre); ?>" required>
            </div>
            <div class="flex flex-col col-span-2">
                <label for="apellido" class="font-bold mb-1">Apellido: </label>
                <input id="apellido" name="apellido" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" value="<?php echo htmlspecialchars($apellido); ?>" required>
            </div>
            <div class="flex flex-col col-span-1">
                <label for="telefono" class="font-bold mb-1">Celular/Telefono: </label>
                <input id="telefono" name="telefono" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" value="<?php echo htmlspecialchars($telefono); ?>" required>
            </div>
            <div class="flex flex-col col-span-2">
                <label for="correo" class="font-bold mb-1">Correo: </label>
                <input id="correo" name="correo" type="email" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" value="<?php echo htmlspecialchars($correo); ?>" required>
            </div>
        </div>
        <div class="flex gap-4">
            <button type="submit" class="flex col-span-5 mt-4 py-1 px-3 bg-blue-600 text-white rounded-md hover:bg-blue-500 transition-all duration-200">
                Guardar Cambios
            </button>
            <button id="closeEditarModal" class="...">Cerrar</button>
        </div>
    </form>
</div>

<script>
    function abrirModalEditar(button) {

        const clienteId = button.getAttribute('data-cliente-id');

        document.getElementById('cliente-id').value = clienteId;  // Establecer el clienteId en el input hidden

    }
</script>
