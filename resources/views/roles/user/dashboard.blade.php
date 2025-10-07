@extends('roles.user.layouts.main')

@section('main-section')

<div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb flex-wrap gap-2">
    <div>
        <p class="fw-medium fs-20 mb-0">Hey, {{ auth('web')->user()->name }} &#128075;</p>

    </div>
</div>
<div class="row">

</div>
@endsection
