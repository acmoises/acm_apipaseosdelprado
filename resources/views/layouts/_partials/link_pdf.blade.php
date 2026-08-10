<!-- Abrir PDF en nueva pestaña -->
@if(session('pdf_url'))
<script type="text/javascript">
    window.open("{{ session('pdf_url') }}", "_blank");
</script>
@endif