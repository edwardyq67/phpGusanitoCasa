
<div class="bg-transparent w-[80%]  rounded-md p-4">

        <button class="py-1 px-3 rounded-t-md bg-blue-600 text-white hover:bg-blue-500 transition-all duration-200">Crear Pedido</button>

        <form method="POST" class="bg-white h-full p-4">
            <div class="rounded-b-md rounded-tr-md text-sm grid grid-cols-5 gap-4 h-auto">
                <div class="flex flex-col col-span-2">
                    <label for="nombre" class="font-bold mb-1">Nombre: </label>
                    <input id="nombre" name="nombre" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" required>
                </div>
                <div class="flex flex-col col-span-2">
                    <label for="apellido" class="font-bold mb-1">Apellido: </label>
                    <input id="apellido" name="apellido" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" required>
                </div>
                <div class="flex flex-col col-span-1">
                    <label for="telefono" class="font-bold mb-1">Celular/Telefono: </label>
                    <input id="telefono" name="telefono" type="text" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" required>
                </div>
                <div class="flex flex-col col-span-2">
                    <label for="correo" class="font-bold mb-1">Correo: </label>
                    <input id="correo" name="correo" type="email" class="bg-zinc-200 focus:outline-none rounded-md py-1 pl-3" required>
                </div>
            </div>
            <div class="flex gap-4">
                <button type="submit" class="flex col-span-5 mt-4 py-1 px-3 bg-blue-600 text-white rounded-md hover:bg-blue-500 transition-all duration-200">
                    Crear
                </button>
                <button id="closeModal" class="flex col-span-5 mt-4 py-1 px-3 bg-red-600 text-white rounded-md hover:bg-red-500 transition-all duration-200">
                    Cerrar
                </button>
            </div>
        </form>
    </div>