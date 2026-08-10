@if ($message = Session::get('success'))
    <div class="p-4 bg-green-500 text-white rounded-md mb-4 shadow-lg border border-green-600">
        <p>{{ $message }}</p>
    </div>
@endif

@if ($message = Session::get('danger'))
    <div class="p-4 bg-red-500 text-white rounded-md mb-4 shadow-lg border border-red-600">
        <p>{{ $message }}</p>
    </div>
@endif