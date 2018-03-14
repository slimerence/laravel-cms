@if(session('msg'))
    <?php $flashMsg = session('msg'); ?>
    <div class="notification is-{{ $flashMsg['status'] }}">
        <button class="delete"></button>
        {{ $flashMsg['content'] }}
    </div>
@endif