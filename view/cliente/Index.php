<?php
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inserción de un nuevo cliente
    if (isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['telefono']) && isset($_POST['correo'])) {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];

        $sql = "INSERT INTO cliente (nombre, apellido, cel, correo) VALUES (:nombre, :apellido, :telefono, :correo)";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al insertar el registro: " . $e->getMessage();
        }
    }

    // Eliminación de un cliente
    if (isset($_POST['cliente_id'])) {
        $cliente_id = $_POST['cliente_id'];

        $sql = "DELETE FROM cliente WHERE id = :cliente_id";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
            $stmt->execute();
            header("Location: index.php?page=cliente"); // Redirigir después de eliminar
            exit();
        } catch (PDOException $e) {
            echo "Error al eliminar el cliente: " . $e->getMessage();
        }
    }
}

// Consulta para obtener todos los clientes
$sql = "SELECT * FROM cliente";
$stmt = $conn->prepare($sql);
$stmt->execute();
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div id="contenido" class="flex flex-col gap-5">
    <section class="bg-slate-50 rounded-md py-4 px-4 flex gap-4 items-center">
        <button id="openModal" class="py-1 px-3 rounded-md bg-green-600 text-white hover:bg-green-500 transition-all duration-200">
            Crear
        </button>
        <form action="" class="relative flex items-center text-sm">
            <label for="Buscar" class="font-bold mr-2">Buscar: </label>
            <input id="Buscar" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3 ">
            <i class="fa-solid fa-magnifying-glass absolute right-0 top-1/2 transform -translate-y-1/2 cursor-pointer text-gray-500 hover:text-gray-800 transition-all duration-200 px-3"></i>
        </form>
        <form action="" class="flex gap-4 items-center text-sm">
            <div class="flex items-center">
                <label for="" class="font-bold mr-2">Desde:</label>
                <input type="date" class="bg-zinc-200 rounded-md py-1 px-2 focus:outline-none">
            </div>
            <div class="flex items-center">
                <label for="" class="font-bold mr-2">Hasta:</label>
                <input type="date" class="bg-zinc-200 rounded-md py-1 px-2 focus:outline-none">
            </div>
        </form>
    </section>
    <section>
        <div class="overflow-hidden bg-white rounded-md">
            <table
                class="min-w-full text-left text-sm font-light text-surface ">
                <thead
                    class=" border-neutral-200 font-medium dark:border-white/10">
                    <tr>
                        <th
                            scope="col"
                            class=" border-neutral-200 px-6 py-4 dark:border-white/10">

                        </th>
                        <th
                            scope="col"
                            class=" border-neutral-200 px-6 py-4 dark:border-white/10">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-4">Apellido</th>
                        <th scope="col" class="px-6 py-4">Cel/telefono</th>
                        <th scope="col" class="px-6 py-4">Correo</th>
                        <th scope="col" class="px-6 py-4">F.Creacion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $cliente): ?>
                        <tr class="border-neutral-200 dark:border-white/10">
                            <td class="whitespace-nowrap flex gap-2 border-neutral-200 px-6 py-4 font-medium dark:border-white/10">
                                <button
                                    class="py-1 px-3 rounded-md bg-yellow-600 text-white hover:bg-yellow-500 transition-all duration-200 openEditarModal"
                                    data-cliente-id="<?php echo htmlspecialchars($cliente['id']); ?>"
                                    onclick="abrirModalEditar(<?php echo htmlspecialchars($cliente['id']); ?>)">
                                    Editar
                                </button>
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="cliente_id" value="<?php echo htmlspecialchars($cliente['id']); ?>">
                                    <button type="submit" class="py-1 px-3 rounded-md bg-red-600 text-white hover:bg-red-500 transition-all duration-200">Eliminar</button>
                                </form>
                                <a href="?page=historialCliente" class="py-1 px-3 rounded-md bg-blue-600 text-white hover:bg-blue-500 transition-all duration-200">Pedido</a>
                            </td>
                            <td class="whitespace-nowrap border-neutral-200 px-6 py-4 dark:border-white/10"><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                            <td class="whitespace-nowrap border-neutral-200 px-6 py-4 dark:border-white/10"><?php echo htmlspecialchars($cliente['apellido']); ?></td>
                            <td class="whitespace-nowrap px-6 py-4"><?php echo htmlspecialchars($cliente['cel']); ?></td>
                            <td class="whitespace-nowrap px-6 py-4"><?php echo htmlspecialchars($cliente['correo']); ?></td>
                            <td class="whitespace-nowrap px-6 py-4"><?php echo htmlspecialchars($cliente['fecha_registro']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
    <section>
        <div>
            <ul class="list-style-none flex">
                <li>
                    <a
                        class="pointer-events-none relative block rounded bg-transparent px-3 py-1.5 text-sm text-surface/50 transition duration-300 dark:text-neutral-400">Previous</a>
                </li>
                <li>
                    <a
                        class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-surface transition duration-300 hover:bg-neutral-100 focus:bg-neutral-100 focus:text-primary-700 focus:outline-none active:bg-neutral-100 active:text-primary-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700  dark:focus:text-primary-500 dark:active:bg-neutral-700 dark:active:text-primary-500"
                        href="#!">1</a>
                </li>
                <li aria-current="page">
                    <a
                        class="relative block rounded bg-primary-100 px-3 py-1.5 text-sm font-medium text-primary-700 transition duration-300 focus:outline-none dark:bg-slate-900 dark:text-primary-500"
                        href="#!">2
                        <span
                            class="absolute -m-px h-px w-px overflow-hidden whitespace-nowrap border-0 p-0 [clip:rect(0,0,0,0)]">(current)</span>
                    </a>
                </li>
                <li>
                    <a
                        class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-surface transition duration-300 hover:bg-neutral-100 focus:bg-neutral-100 focus:text-primary-700 focus:outline-none active:bg-neutral-100 active:text-primary-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:focus:text-primary-500 dark:active:bg-neutral-700 dark:active:text-primary-500"
                        href="#!">3</a>
                </li>
                <li>
                    <a
                        class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-surface transition duration-300 hover:bg-neutral-100 focus:bg-neutral-100 focus:text-primary-700 focus:outline-none active:bg-neutral-100 active:text-primary-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:focus:text-primary-500 dark:active:bg-neutral-700 dark:active:text-primary-500"
                        href="#!">Next</a>
                </li>
            </ul>
        </div>
    </section>
</div>


<div id="crear" class="fixed inset-0 hidden flex items-center justify-center bg-black bg-opacity-50 ">
    <?php include('Post.php') ?>
</div>
<div id="editar" class="fixed inset-0 hidden flex items-center justify-center bg-black bg-opacity-50 ">
    <?php include('Put.php') ?>
</div>
<style>
    #contenido {
        display: grid;
        min-height: 88vh;
        grid-template-rows:
            auto 1fr auto;
    }
</style>

<script>
    const openCrearModal = document.getElementById('openModal');
    const closeCrearModal = document.getElementById('closeModal');
    const crearModal = document.getElementById('crear');

    openCrearModal.addEventListener('click', () => {
        crearModal.classList.remove('hidden');
    });

    closeCrearModal.addEventListener('click', () => {
        crearModal.classList.add('hidden');
    });

    window.addEventListener('click', (event) => {
        if (event.target === crearModal) {
            crearModal.classList.add('hidden');
        }
    });

    const editarModal = document.getElementById('editar');
    const openEditarButtons = document.querySelectorAll('.openEditarModal');

    openEditarButtons.forEach(button => {
        button.addEventListener('click', () => {
            editarModal.classList.remove('hidden');
        });
    });

    const closeEditarModal = document.getElementById('closeEditarModal');
    closeEditarModal.addEventListener('click', () => {
        editarModal.classList.add('hidden');
    });

    window.addEventListener('click', (event) => {
        if (event.target === editarModal) {
            editarModal.classList.add('hidden');
        }
    });
</script>