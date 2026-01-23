<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Importar Productes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Èxit!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @if(session('failures'))
                         <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <strong>Errors de validació:</strong>
                            <ul class="list-disc pl-5 mt-2">
                                @foreach(session('failures') as $failure)
                                    <li>
                                        Fila {{ $failure->row() }}: {{ implode(', ', $failure->errors()) }}
                                        (Atribut: {{ $failure->attribute() }})
                                    </li>
                                @endforeach
                            </ul>
                         </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('products.import.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700">Selecciona un fitxer Excel or CSV</label>
                            <input type="file" name="file" id="file" required accept=".xlsx,.xls,.csv" 
                                class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                            <p class="mt-1 text-sm text-gray-500">Formats suportats: .xlsx, .xls, .csv</p>
                        </div>

                        <div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Importar
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 border-t pt-4">
                        <h3 class="text-lg font-medium text-gray-900">Format esperat</h3>
                        <p class="text-sm text-gray-600 mt-2">
                            L'arxiu ha de tenir la següent capçalera (fila 1):
                        </p>
                        <code class="block bg-gray-100 p-2 mt-2 rounded">
                            sku, name, description, price, stock, category, image
                        </code>
                        <ul class="list-disc list-inside text-sm text-gray-600 mt-2">
                            <li><strong>sku</strong>: Únic (Obligatori)</li>
                            <li><strong>name</strong>: Nom del producte (Obligatori)</li>
                            <li><strong>price</strong>: Numèric (Obligatori)</li>
                            <li><strong>stock</strong>: Enter (Obligatori)</li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
