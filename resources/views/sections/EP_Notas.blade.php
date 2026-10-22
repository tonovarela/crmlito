<div class="col-md-12">
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">
                <i class="ion ion-clipboard mr-1"></i> Notas
            </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body card-notes">

            <!-- <div class="callout callout-danger"> -->
            <!-- <div class="user-block col-md-12 user-block-notes">
                    <img class="img-circle img-bordered-sm" src="{{ asset('img/user.png') }}" alt=" User Image ">
                    <span class="username user-notes ">
                      <a class="a-notes" id="usuario-notes">Sarah Ross</a> -->
            <!-- <a href="#" class="float-right btn-tool"><i class="fas fa-pen"></i></a>
                      <a href="#" class="float-right btn-tool"><i class="fas fa-save"></i></a> -->
            <!-- <a href="# " class="float-right btn-tool "><i class="fas fa-times "></i></a> -->
            <!-- </span>
                    <span class="description date-notes" id="fecha-notes"> Sent you a message - 3 days ago</span>
                </div> -->
            <!-- <h5>I am a danger callout!</h5> -->
            <!-- <input type="text" id="description-notes" class="form-control" value="There is a problem that we need to fix. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart."> -->
            <!-- <p>There is a problem that we need to fix. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart.</p> -->
            <!-- </div> -->


        </div>
        <!-- <form class="form-notas" method="post" role="form" action="{{ route('elementos.Addnotas') }}" id="frm-Notas"> -->
        <div class="input-group input-group-sm mb-0 input-notes">
            <input class="form-control form-control-sm " placeholder="Nueva Nota... " id="Nota" name="Nota">
            <div class="input-group-append ">
                <button class="btn btn-danger " id="saveNote">Guardar</button>
            </div>
        </div>
        <!-- </form> -->
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<script src=" {{ asset('js/elementos_presupuestados_notes.js?n=1') }} " defer></script>