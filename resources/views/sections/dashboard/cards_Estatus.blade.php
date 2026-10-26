<div class="row" id='cardsDashboard'>


</div>
<script src=" {{ asset('js/dashboards_Estatus.js?n=2') }} " defer></script>
<script>
    function gotoOport(evento) {

        sessionStorage.setItem("evento", evento.trim());
        window.location.href = "oportunidades";
        // $("#searchGral").val(evento.trim());

        // // window.location.replace(base_path+"/oportunidades?evento="+evento);

        // alert(evento)
    }
</script>
