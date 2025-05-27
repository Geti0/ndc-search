<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white shadow-md rounded-lg p-6 w-full h-full flex flex-col items-center">

                <h1 class="font-bold text-center text-black mb-6">
                    Aplikacioni për Kërkimin e Ilaqeve
                </h1>

                <form id="search-form" action="{{ route('search.submit') }}" method="POST" class="mb-6 w-full flex flex-col items-center">
                    @csrf
                    <div class="flex flex-row items-center gap-4 w-full max-w-4xl">
                        <input
                            type="text"
                            name="ndc_codes"
                            class="w-full ml-6 px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-italic placeholder-gray-400"
                            placeholder="Shkruaj kodet të ndara me presje, 12345-6789, 11111-2222, 99999-0000"
                            required
                        >
                        <button
                            id="submit-btn"
                            type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-black font-semibold px-6 py-2 rounded-md shadow-md transition duration-150 flex items-center justify-center h-[42px]"
                        >
                            <span id="submit-text">Kërko</span>
                            <div id="spinner" class="hidden spinner h-5 w-5 ml-2"></div>
                        </button>
                    </div>
                </form>

                @if(session('last_results'))
                    <a href="{{ route('search.export') }}"
                       class="inline-block mb-4 bg-green-500 hover:bg-green-600 text-black text-sm font-medium px-4 py-2 rounded shadow-md">
                        Eksporto në CSV
                    </a>
                @endif

               @isset($results)

                    <div class="overflow-x-auto w-full flex flex-col items-center">
                        <table class="min-w-full text-sm text-left border border-gray-200">
                            <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-2 border">Kodi</th>
                                    <th class="px-4 py-2 border">Emri i produktit</th>
                                    <th class="px-4 py-2 border">Prodhuesi</th>
                                    <th class="px-4 py-2 border">Lloji i produktit</th>
                                    <th class="px-4 py-2 border">Burimi</th>
                                    <th class="px-4 py-2 border">Fshije</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-800">
                                @foreach ($results as $r)
                                    <tr class="bg-white even:bg-gray-50">
                                        <td class="px-4 py-2 border">{{ $r['ndc_code'] }}</td>
                                        <td class="px-4 py-2 border">{{ $r['brand_name'] ?? '-' }}</td>
                                        <td class="px-4 py-2 border">{{ $r['labeler_name'] ?? '-' }}</td>
                                        <td class="px-4 py-2 border">{{ $r['product_type'] ?? '-' }}</td>
                                        <td class="px-4 py-2 border">{{ $r['source'] }}</td>
                                        <td class="px-4 py-2 border text-center">
                                            @if($r['source'] === 'Database')
                                                <form action="{{ route('ndc.delete', $r['id']) }}" method="POST" onsubmit="return confirm('A jeni i sigurt që doni ta fshini këtë produkt?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-black px-3 py-1 rounded">
                                                        Fshije
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                    <div class="mt-4">
                        {{ $results->links() }}
                    </div>
                @endisset

            </div>
        </div>
    </div>

    <style>
        .spinner {
            border: 2px solid black;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <script>
        const form = document.getElementById('search-form');
        const button = document.getElementById('submit-btn');
        const spinner = document.getElementById('spinner');
        const submitText = document.getElementById('submit-text');

        form.addEventListener('submit', function () {
            button.disabled = true;
            submitText.classList.add('hidden');
            spinner.classList.remove('hidden');
        });
    </script>
</x-app-layout>
