@if(session('msg'))
    <?php $flashMsg = session('msg'); ?>
    <div class="container p-0 mt-1">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-{{ $flashMsg['status'] }} alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>{{ $flashMsg['content'] }}</strong>
                </div>
            </div>
        </div>
    </div>
@endif