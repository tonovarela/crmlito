<!-- Modal -->
<div class="modal fade" id="mod_TipoPaper" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"> <input type="text" id="nameColumn" readonly hidden>
                <h5 class="modal-title" id="staticBackdropLabel">Catálogo de Papel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
            </div>
            <div class="modal-body modPapel">
                <div class="input-group input-group-sm">
                    <input type="number" id="idSustrato" name="idSustrato" readonly hidden>
                    <div class="row col-12">
                        <div class="col-6">
                            <div class="form-group ">
                                <label for="tipo_item ">Tipo Item</label>
                                <select class="input-group select2 sltPapel sltClasificacion " style="width: 100%; " id="TPItem" name="TPItem">
                                        <option value='0' selected="selected" >Seleccionar...</option>
                                        @foreach ($dto->combo_tipoPapelItem as $clasificacion)
                                        <option value="{{ $clasificacion->clasificacion}}">{{ $clasificacion->clasificacion }}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group ">
                                <label for="tipo_item ">Código</label>
                                <select class="input-group select2 sltPapel sltCodigo " style="width: 100%; " id="sltCodigo" name="sltCodigo">
                                    <option value='0'  selected="selected " >Seleccionar...</option>
                                    
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row col-12">
                        <div class="col-6">
                            <div class="form-group ">
                                <label for="tipo_item ">Descripcón</label>
                                <div class="input-group">
                                    <input id="EPDescripcion" name="EPDescripcion" type="text" class="form-control" placeholder="Descripción" autocomplete="off" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group ">
                                <label for="tipo_item ">Gramajes</label>
                                <select class="input-group select2 sltPapel " style="width: 100%; " id="sltGramajes" name="sltGramajes"> 
                                    <option value='0'  selected="selected " >Seleccionar...</option>
                                   
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnAddSustrato">Agregar</button>
                </div>
            </div>
        </div>
    </div>