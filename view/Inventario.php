<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($conn)) {
        $isbn = isset($_POST['segundoFormISBN']) ? $_POST['segundoFormISBN'] : '';
        $sku = isset($_POST['segundoFormSKU']) ? $_POST['segundoFormSKU'] : '';
        $nombreProducto = isset($_POST['NombProducto']) ? $_POST['NombProducto'] : '';
        $precio = isset($_POST['precio']) ? $_POST['precio'] : '';
        $cantidad = isset($_POST['cantida']) ? $_POST['cantida'] : '';

        $sql = "INSERT INTO libro (isbn, sku, nombreP, cantidad, precio) VALUES (:isbn, :sku, :nombreProducto, :cantidad, :precio)";

        try {
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':isbn', $isbn);
            $stmt->bindParam(':sku', $sku);
            $stmt->bindParam(':nombreProducto', $nombreProducto);
            $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmt->bindParam(':precio', $precio);

            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al insertar el registro: " . $e->getMessage();
        }
    } else {
        echo "Error: La conexión no se ha establecido.";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete') {
    if (isset($conn)) {
        $idEliminar = isset($_POST['idEliminar']) ? $_POST['idEliminar'] : '';

        if ($idEliminar) {
            // Consulta para eliminar el libro
            $sql = "DELETE FROM libro WHERE id = :id";

            try {
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $idEliminar, PDO::PARAM_INT);

                $stmt->execute();
                header("Location: index.php?page=inventario");
                exit();
            } catch (PDOException $e) {
                echo "Error al eliminar el registro: " . $e->getMessage();
            }
        }
    } else {
        echo "Error: La conexión no se ha establecido.";
    }
}
$sql = "SELECT * FROM libro";
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
                            Codigo ISBN
                        </th>
                        <th
                            scope="col"
                            class=" border-neutral-200 px-6 py-4 dark:border-white/10">
                            Codigo SKU
                        </th>
                        <th scope="col" class="px-6 py-4">Nom. Prducto</th>
                        <th scope="col" class="px-6 py-4">Cantidad</th>
                        <th scope="col" class="px-6 py-4">Precio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $libro): ?>
                        <tr class="border-neutral-200 dark:border-white/10">
                            <td class="whitespace-nowrap flex gap-2 border-neutral-200 px-6 py-4 font-medium dark:border-white/10">
                                <button id="openModalCrear" class="py-1 px-3 rounded-md bg-yellow-600 text-white hover:bg-yellow-500 transition-all duration-200">Editar</button>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="idEliminar" value="<?php echo $libro['id']; ?>">
                                    <button type="submit" class="py-1 px-3 rounded-md bg-red-600 text-white hover:bg-red-500 transition-all duration-200">Eliminar</button>
                                </form>
                            </td>
                            <td class="whitespace-nowrap border-neutral-200 px-6 py-4 dark:border-white/10">
                                <?php echo htmlspecialchars($libro['isbn']); ?>
                            </td>
                            <td class="whitespace-nowrap border-neutral-200 px-6 py-4 dark:border-white/10">
                                <?php echo htmlspecialchars($libro['sku']); ?>
                            </td>
                            <td class="whitespace-nowrap border-neutral-200 px-6 py-4 dark:border-white/10">
                                <?php echo htmlspecialchars($libro['nombreP']); ?>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <?php echo htmlspecialchars($libro['cantidad']); ?>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <?php echo htmlspecialchars($libro['precio']); ?>
                            </td>
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
                        class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-surface transition duration-300 hover:bg-neutral-100 focus:bg-neutral-100 focus:text-primary-700 focus:outline-none active:bg-neutral-100 active:text-primary-700  dark:hover:bg-neutral-700 dark:focus:bg-neutral-700  dark:focus:text-primary-500 dark:active:bg-neutral-700 dark:active:text-primary-500"
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
                        class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-surface transition duration-300 hover:bg-neutral-100 focus:bg-neutral-100 focus:text-primary-700 focus:outline-none active:bg-neutral-100 active:text-primary-700  dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:focus:text-primary-500 dark:active:bg-neutral-700 dark:active:text-primary-500"
                        href="#!">3</a>
                </li>
                <li>
                    <a
                        class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-surface transition duration-300 hover:bg-neutral-100 focus:bg-neutral-100 focus:text-primary-700 focus:outline-none active:bg-neutral-100 active:text-primary-700  dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:focus:text-primary-500 dark:active:bg-neutral-700 dark:active:text-primary-500"
                        href="#!">Next</a>
                </li>
            </ul>
        </div>
    </section>
</div>


<div id="crear" class="fixed inset-0 hidden flex items-center justify-center bg-black bg-opacity-50 ">
    <div class="bg-transparent w-[80%]  rounded-md p-4">

        <button class="py-1 px-3 rounded-t-md bg-blue-600 text-white hover:bg-blue-500 transition-all duration-200">Inventario</button>

        <form id="formCodigo" class="bg-white h-full p-4">
            <div class=" rounded-b-md rounded-tr-md text-sm grid gap-4 h-auto justify-center items-center text-center">
                <div>
                    <label for="isbnInput">
                        <img class="blur-sm w-40 h-auto" src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEg6HkGGUlZna7sbOqOQuhQn9XdKLpOYmlr1kmOMT0KY0QNvtsOBQTl7gvkgK1QRmXuonz1Pg2NfBg1clljFEF5z01NwGbDQXo-dODhM1ECllzRhMbACM7Q82QZqkor0k06adj1H2r6GWWum/s1600/Qu%25C3%25A9+es+un+ISBN.png" alt="">
                    </label>
                    <input id="isbnInput" maxlength="13"
                        minlength="13"
                        pattern="\d{13}" name="ISBN" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3 opacity-0" placeholder="ISBN">
                </div>
                <span>ó</span>
                <input id="SKUInput" type="text" maxlength="14"
                    minlength="14"
                    pattern="\d{14}" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" placeholder="SKU">
            </div>
            <div class="flex gap-4">
                <button id="GuardarCodigo" class="flex col-span-5 mt-4 py-1 px-3 bg-blue-600 text-white rounded-md hover:bg-blue-500 transition-all duration-200">
                    Guardar
                </button>
                <button id="closeModal" class="flex col-span-5 mt-4 py-1 px-3 bg-red-600 text-white rounded-md hover:bg-red-500 transition-all duration-200">
                    Cancelar
                </button>
            </div>
        </form>

        <form id="formInfoCodigo" method="POST" class="bg-white h-full p-4 hidden text-sm">
            <input type="hidden" name="accion" value="crear">
            <div class="grid grid-cols-4 gap-4">
                <div class="flex flex-col col-span-2">
                    <label for="segundoFormISBN" class="font-bold mb-1">ISBN: </label>
                    <input name="segundoFormISBN" id="segundoFormISBN" value="isbnInput" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3">
                </div>
                <div class="flex flex-col col-span-2">
                    <label for="segundoFormSKU" class="font-bold mb-1">SKU: </label>
                    <input name="segundoFormSKU" id="segundoFormSKU" value="SKUInput" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3">
                </div>
                <div class="flex flex-col col-span-2">
                    <label for="NombProducto" class="font-bold mb-1">Nombre del Producto: </label>
                    <input name="NombProducto" id="NombProducto" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3">
                </div>
                <div class="flex flex-col col-span-1">
                    <label for="precio" class="font-bold mb-1">Precio: </label>
                    <input name="precio" id="precio" type="number" step="0.01" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3">
                </div>

                <div class="flex flex-col col-span-1">
                    <label for="cantidad" class="font-bold mb-1">Cantidad: </label>
                    <input name="cantida" id="cantidad" type="number" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3">
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex col-span-5 mt-4 py-1 px-3 bg-blue-600 text-white rounded-md hover:bg-blue-500 transition-all duration-200">
                    Guardar
                </button>
                <button id="closeModal" class="flex col-span-5 mt-4 py-1 px-3 bg-red-600 text-white rounded-md hover:bg-red-500 transition-all duration-200">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<div id="editar" class="fixed inset-0 hidden flex items-center justify-center bg-black bg-opacity-50 ">
    <div class="bg-transparent w-[80%]  rounded-md p-4">

        <button class="py-1 px-3 rounded-t-md bg-blue-600 text-white hover:bg-blue-500 transition-all duration-200">Inventario</button>

        <form id="formCodigoEdit" class="bg-white h-full p-4">
            <div class=" rounded-b-md rounded-tr-md text-sm grid gap-4 h-auto justify-center items-center text-center">
                <div>
                    <label for="isbnInputEditar">
                        <img class="blur-sm w-40 h-auto" src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEg6HkGGUlZna7sbOqOQuhQn9XdKLpOYmlr1kmOMT0KY0QNvtsOBQTl7gvkgK1QRmXuonz1Pg2NfBg1clljFEF5z01NwGbDQXo-dODhM1ECllzRhMbACM7Q82QZqkor0k06adj1H2r6GWWum/s1600/Qu%25C3%25A9+es+un+ISBN.png" alt="">
                    </label>
                    <input id="isbnInputEditar" maxlength="13"
                        minlength="13"
                        pattern="\d{13}" name="ISBN" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3 opacity-0" placeholder="ISBN">
                </div>
                <span>ó</span>
                <input id="SKUInputcrear" type="text" maxlength="14"
                    minlength="14"
                    pattern="\d{14}" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" placeholder="SKU">
            </div>
            <div class="flex gap-4">
                <button id="GuardarCodigoEditar" class="flex col-span-5 mt-4 py-1 px-3 bg-blue-600 text-white rounded-md hover:bg-blue-500 transition-all duration-200">
                    Guardar
                </button>
                <button id="closeModalEditar" class="flex col-span-5 mt-4 py-1 px-3 bg-red-600 text-white rounded-md hover:bg-red-500 transition-all duration-200">
                    Cancelar
                </button>
            </div>
        </form>

        <form id="formInfoCodigoEdit" method="POST" class="bg-white h-full p-4 hidden text-sm">
            <input type="hidden" name="accion" value="editar">
            <div class="grid grid-cols-4 gap-4">
                <div class="flex flex-col col-span-2">
                    <label for="segundoFormISBNEdit" class="font-bold mb-1">ISBN: </label>
                    <input name="segundoFormISBNEdit" id="segundoFormISBN" value="isbnInput" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3">
                </div>
                <div class="flex flex-col col-span-2">
                    <label for="segundoFormSKUEdit" class="font-bold mb-1">SKU: </label>
                    <input name="segundoFormSKUEdit" id="segundoFormSKU" value="SKUInput" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3">
                </div>
                <div class="flex flex-col col-span-2">
                    <label for="NombProductoEdit" class="font-bold mb-1">Nombre del Producto: </label>
                    <input name="NombProductoEdit" id="NombProducto" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3">
                </div>
                <div class="flex flex-col col-span-1">
                    <label for="precioEdit" class="font-bold mb-1">Precio: </label>
                    <input name="precioEdit" id="precio" type="number" step="0.01" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3">
                </div>

                <div class="flex flex-col col-span-1">
                    <label for="cantidadEdit" class="font-bold mb-1">Cantidad: </label>
                    <input name="cantidaEdit" id="cantidad" type="number" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3">
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex col-span-5 mt-4 py-1 px-3 bg-blue-600 text-white rounded-md hover:bg-blue-500 transition-all duration-200">
                    Guardar
                </button>
                <button id="closeModalEditar" class="flex col-span-5 mt-4 py-1 px-3 bg-red-600 text-white rounded-md hover:bg-red-500 transition-all duration-200">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
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
    // modal crear
    const openModal = document.getElementById('openModal');
    const closeModal = document.getElementById('closeModal');
    const modal = document.getElementById('crear');
    const isbnInput = document.getElementById('isbnInput');
    const skuInput = document.getElementById('SKUInput')
    const GuardarCodigo = document.getElementById('GuardarCodigo')

    openModal.addEventListener('click', () => {
        modal.classList.remove('hidden');
        isbnInput.focus();
    });

    closeModal.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });
    // validacion del codigo
    const formCodigo = document.getElementById('formCodigo');
    const formInfoCodigo = document.getElementById('formInfoCodigo');
    const segundoFormISBN = document.getElementById('segundoFormISBN');
    const segundoFormSKU = document.getElementById('segundoFormSKU');

    document.getElementById('GuardarCodigo').addEventListener('click', (event) => {
        event.preventDefault();

        const isbnInputValid = isbnInput.value.length === 13 && /^\d{13}$/.test(isbnInput.value);
        const skuInputValid = skuInput.value.length === 14

        if (isbnInputValid || skuInputValid) {

            formCodigo.classList.add('hidden');

            formInfoCodigo.classList.remove('hidden');

            if (isbnInput) {
                segundoFormISBN.value = isbnInput.value

            }
            if (SKUInput) {
                segundoFormSKU.value = SKUInput.value
            }
        } else {
            alert("Por favor, ingresa un ISBN de 13 dígitos o un SKU de 14 dígitos.");
        }
    });

    // Modal y Formulario
    const openModalCrear = document.getElementById('openModalCrear');
    const editar = document.getElementById('editar');
    const isbnInputEditar = document.getElementById('isbnInputEditar');
    const SKUInputEditar = document.getElementById('SKUInputcrear'); // Corregido el id aquí
    const closeModalEditar = document.getElementById('closeModalEditar');

    openModalCrear.addEventListener('click', () => {
        editar.classList.remove('hidden');
        isbnInputEditar.focus();
    });

    closeModalEditar.addEventListener('click', () => {
        editar.classList.add('hidden');
    });

    window.addEventListener('click', (event) => {
        if (event.target === editar) {
            editar.classList.add('hidden');
        }
    });

    // Validación del formulario de códigos
    const formCodigoEdit = document.getElementById('formCodigoEdit');
    const formInfoCodigoEdit = document.getElementById('formInfoCodigoEdit');
    const segundoFormISBNEdit = document.getElementById('segundoFormISBN');
    const segundoFormSKUEdit = document.getElementById('segundoFormSKU');

    document.getElementById('GuardarCodigoEditar').addEventListener('click', (event) => {
        event.preventDefault();

        const isbnInputEditarValidar = isbnInputEditar.value.length === 13 && /^\d{13}$/.test(isbnInputEditar.value);

        const SKUInputEditarValidar = SKUInputEditar.value.length === 14 && /^\d{14}$/.test(SKUInputEditar.value);

        if (isbnInputEditarValidar || SKUInputEditarValidar) {
            formCodigoEdit.classList.add('hidden');
            formInfoCodigoEdit.classList.remove('hidden');

            if (isbnInputEditarValidar) {
                segundoFormISBNEdit.value = isbnInputEditar.value;
            }

            if (SKUInputEditarValidar) {
                segundoFormSKUEdit.value = SKUInputEditar.value;
            }
        } else {
            alert('Por favor, ingrese un ISBN de 13 dígitos o un SKU de 14 dígitos.');
        }
    });
</script>