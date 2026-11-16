@props(['value' => '', 'placeholder' => 'Search…'])

<form method="GET" class="form-inline">
    <div class="input-group input-group-sm" style="width: 260px">
        <input type="search" name="q" value="{{ $value }}" class="form-control" placeholder="{{ $placeholder }}" aria-label="Search">
        <div class="input-group-append">
            <button class="btn btn-default" type="submit"><i class="fas fa-search"></i></button>
        </div>
    </div>
</form>
