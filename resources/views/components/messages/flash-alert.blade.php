@if (Session::has('error_message'))
    <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show" role="alert" data-bs-dismiss="alert">
        <x-heroicon-o-exclamation-triangle class="icon me-1"/> {{ Session::get('error_message') }}
    </div>
@elseif(Session::has('success_message'))
    <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" role="alert" data-bs-dismiss="alert">
        <x-heroicon-o-check-badge class="icon me-1"/> {{ Session::get('success_message') }}
    </div>
@endif