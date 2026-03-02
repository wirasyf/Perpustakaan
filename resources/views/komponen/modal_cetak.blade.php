<!-- Tombol trigger -->
<button onclick="document.getElementById('modalExport').classList.remove('hidden')"
        class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">
    Export Excel
</button>

<!-- Modal -->
<div id="modalExport" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">

        <h2 class="text-lg font-bold text-center text-white bg-blue-800 -mx-6 -mt-6 px-6 py-4 rounded-t-xl mb-6">
            Filter Data Export Buku
        </h2>

        <form action="{{ route('buku.export') }}" method="GET">
            <div class="mb-6">
                <label class="block text-sm font-semibold mb-2">Kategori</label>
                <select name="kategori"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="semua">Semua</option>
                    <option value="fiksi">Fiksi</option>
                    <option value="nonfiksi">Non Fiksi</option>
                </select>
            </div>

            <div class="flex justify-between items-center gap-3">
                <button type="button"
                        onclick="document.getElementById('modalExport').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100">
                    Reset
                </button>
                <div class="flex gap-2">
                    <button type="button"
                            onclick="document.getElementById('modalExport').classList.add('hidden')"
                            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 bg-blue-800 text-white rounded-lg hover:bg-blue-900 font-semibold">
                        Terapkan
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>