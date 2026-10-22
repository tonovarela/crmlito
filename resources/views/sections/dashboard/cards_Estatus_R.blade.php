<div class="row" id="dashboard_cards">
    @foreach ($my_array as $card)
    <div class="col-lg-1 col-1 div-card">
        <!-- small box -->

        <div class="small-box " style="background-color: {{$card->colorEstatus}};color: {{$card->color}}; ">
            <div class="inner">
                <h3>{{$card->Cantidad}}</h3>

                <p>{{$card->descEstatus}}</p>
            </div>
            <div class="icon">
                <i class="{{$card->icon}}" style="font-size: 4rem !important;"></i>
            </div>
            <a onclick="gotoOport('{{$card->descEstatus}}')" class="small-box-footer cursor-pointer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    @endforeach

</div>
<script>
    function gotoOport(evento)
    {
        sessionStorage.setItem("evento", evento.trim());
        window.location.href = "oportunidades";
    }
</script>
