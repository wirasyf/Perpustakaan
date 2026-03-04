{{-- 
    Modal Cetak Laporan - Reusable Component
    
    Usage: @include('components.modal-cetak', [
        'modalId'   => 'modalCetakBuku',           // unique ID
        'title'     => 'Filter Data Cetak Laporan', // modal header text
        'filters'   => [                            // array of select filters
            ['id' => 'cetakKategori', 'label' => 'Kategori', 'placeholder' => 'Pilih Kategori', 'options' => [
                ['value' => 'fiksi', 'label' => 'Fiksi'],
                ['value' => 'nonfiksi', 'label' => 'Non Fiksi'],
            ]],
    ],
        'routes' => [                               // export routes
            'pdf'   => route('cetak.buku.pdf'),
            'excel' => route('cetak.buku.excel'),
    ],
        'formats' => ['pdf', 'excel'],              // available formats (default: both)
])
--}}

@php
    $modalId   = $modalId ?? 'modalCetakLaporan';
    $title     = $title ?? 'Filter Data Cetak Laporan';
    $filters   = $filters ?? [];
    $routes    = $routes ?? [];
    $formats   = $formats ?? ['pdf', 'excel'];
@endphp

<div class="modal-cetak-overlay" id="{{ $modalId }}">
    <div class="modal-cetak">
        <div class="modal-cetak-header">{{ $title }}</div>
        <div class="modal-cetak-body">
            <hr>
@foreach($filters as $filter)
<div class="filter-item">
    <label>{{ $filter['label'] }}</label>

    @if(isset($filter['type']) && $filter['type'] === 'date')
        {{-- Input tanggal --}}
        <input type="date" name="{{ $filter['id'] }}" data-param="{{ $filter['id'] }}"
               class="filter-input-date">
    @else
        {{-- Dropdown seperti sebelumnya --}}
        <select name="{{ $filter['id'] }}" data-param="{{ $filter['id'] }}">
            <option value="" disabled selected>{{ $filter['placeholder'] ?? 'Pilih' }}</option>
            @if($filter['allOption'] ?? false)
                <option value="semua">Semua</option>
            @endif
            @foreach($filter['options'] ?? [] as $opt)
                <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
            @endforeach
        </select>
    @endif
</div>
@endforeach

            <label>Format</label>
            <select id="{{ $modalId }}_format" data-param="format">
                <option value="" selected>Pilih Format</option>
                @if(in_array('pdf', $formats))
                    <option value="pdf">PDF</option>
                @endif
                @if(in_array('excel', $formats))
                    <option value="excel">Excel</option>
                @endif
            </select>
        </div>
        <div class="modal-cetak-footer">
            <button type="button" class="btn-reset" data-modal="{{ $modalId }}">Reset</button>
            <div class="right-btns">
                <button type="button" class="btn-batal" data-modal="{{ $modalId }}">Batal</button>
                <button type="button" class="btn-terapkan" data-modal="{{ $modalId }}"
                    @if(isset($routes['pdf']))  data-pdf="{{ $routes['pdf'] }}"   @endif
                    @if(isset($routes['excel'])) data-excel="{{ $routes['excel'] }}" @endif
                >Terapkan</button>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    const modalId = '{{ $modalId }}';
    const overlay = document.getElementById(modalId);
    if(!overlay) return;

    const selects = overlay.querySelectorAll('select');
    const formatSel = document.getElementById(modalId + '_format');

    function closeModal(){ overlay.classList.remove('show'); }

    overlay.addEventListener('click', function(e){ if(e.target === overlay) closeModal(); });

    overlay.querySelector('.btn-batal').addEventListener('click', closeModal);

    overlay.querySelector('.btn-reset').addEventListener('click', function(){
        selects.forEach(s => s.value = '');
    });

    overlay.querySelector('.btn-terapkan').addEventListener('click', function(){
        const format = formatSel.value;
        if(!format){ alert('Pilih format terlebih dahulu'); return; }

        let params = new URLSearchParams();
// Ambil value dari select
selects.forEach(function(sel){
    const param = sel.dataset.param;
    if(param === 'format') return;
    const val = sel.value;
    if(val && val !== 'semua') params.set(param, val);
});

// Ambil value dari input date
overlay.querySelectorAll('input[type="date"]').forEach(function(input){
    const param = input.dataset.param;
    const val = input.value;
    if(val) params.set(param, val);
});

        const btn = this;
        let url = '';
        if(format === 'pdf' && btn.dataset.pdf){
            url = btn.dataset.pdf;
            if(params.toString()) url += '?' + params.toString();
            window.open(url, '_blank');
        } else if(format === 'excel' && btn.dataset.excel){
            url = btn.dataset.excel;
            if(params.toString()) url += '?' + params.toString();
            window.location.href = url;
        } else {
            alert('Format ' + format.toUpperCase() + ' tidak tersedia');
            return;
        }
        closeModal();
    });
})();
</script>
