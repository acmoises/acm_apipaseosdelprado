@extends('layouts.bitacoras')

@section('content')
<div class="max-w-6xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold text-gray-900">Calendario de Bitácora</h2>
        <a href="{{ route('bitacoras.index') }}" 
           class="px-4 py-2 bg-principal text-white rounded-md hover:bg-terciario focus:outline-none focus:ring-2 focus:ring-principal focus:ring-opacity-50">
            Volver
        </a>
    </div>

    <div id="calendar" class="w-full" style="height: 700px;"></div>
</div>

{{-- ✅ FullCalendar CSS --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">

{{-- ✅ FullCalendar JS --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log("📅 Cargando calendario...");

    const calendarEl = document.getElementById('calendar');

    // Verifica si FullCalendar está cargado
    if (typeof FullCalendar === 'undefined') {
        console.error('❌ FullCalendar no se ha cargado correctamente');
        return;
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: @json($events),
        eventClick: function(info) {
            if(info.event.url){
                window.location.href = info.event.url;
                info.jsEvent.preventDefault();
            }
        },
        height: 'auto',
        expandRows: true
    });

    calendar.render();
});
</script>
@endsection
