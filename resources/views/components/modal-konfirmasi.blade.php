{{-- 
    Modal Konfirmasi - Reusable Component
    Usage: @include('components.modal-konfirmasi', [
        'modalId' => 'modalDelete',
        'title'   => 'Hapus Data?',
        'message' => 'Apakah Anda yakin ingin menghapus data ini?',
        'type'    => 'danger', // danger, warning, success
        'confirmBtnText' => 'Hapus',
        'cancelBtnText'  => 'Batal'
    ])
--}}

@php
    $modalId = $modalId ?? 'modalKonfirmasi';
    $title   = $title ?? 'Konfirmasi';
    $message = $message ?? 'Apakah Anda yakin?';
    $type    = $type ?? 'danger';
    $confirmBtnText = $confirmBtnText ?? 'Ya, Lanjutkan';
    $cancelBtnText  = $cancelBtnText ?? 'Batal';
@endphp

<div class="modal-konfirmasi-overlay" id="{{ $modalId }}">
    <div class="modal-konfirmasi">
        <div class="modal-konfirmasi-body">
            <div class="icon-wrapper {{ $type }}">
                @if($type == 'danger')
                    <i class="fa-solid fa-triangle-exclamation"></i>
                @elseif($type == 'warning')
                    <i class="fa-solid fa-circle-exclamation"></i>
                @else
                    <i class="fa-solid fa-circle-check"></i>
                @endif
            </div>
            <h3>{{ $title }}</h3>
            <p>{{ $message }}</p>
        </div>
        <div class="modal-konfirmasi-footer">
            <button type="button" class="btn-cancel" onclick="closeKonfirmasiModal('{{ $modalId }}')">{{ $cancelBtnText }}</button>
            <button type="button" class="btn-confirm {{ $type }}" id="btnConfirm_{{ $modalId }}">{{ $confirmBtnText }}</button>
        </div>
    </div>
</div>

<script>
    function closeKonfirmasiModal(id) {
        document.getElementById(id).classList.remove('show');
    }

    // Modal behavior
    (function() {
        const modal = document.getElementById('{{ $modalId }}');
        if (!modal) return;

        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeKonfirmasiModal('{{ $modalId }}');
        });
    })();
</script>
