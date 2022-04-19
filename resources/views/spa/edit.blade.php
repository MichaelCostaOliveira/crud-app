@extends('layout.app', [
'elementActive' => 'Incidentes'
])
@section('content')
<style>
   .dropzone .dz-preview {
      zoom: 0.8;
   }

   .custom-control-label {
      cursor: pointer !important;
   }
   .text-adm { color: #66619c !important;}
</style>
<!-- BEGIN: Content-->
<div class="content-wrapper" data-aos=fade-left data-aos-delay=0>
   <input type="hidden" value="{{$user->id ?? ''}}" name="iduser" id="iduser">

   <div class="content-body">
      <section class="app-user-view-account">
         <div class="row">
            <div class="col-12">
               <ul class="nav nav-pills" role="tablist">
                  <li class="nav-item">
                     <a class="nav-link d-flex align-items-center active" id="information-tab" data-toggle="tab" href="#information" aria-controls="information" role="tab" aria-selected="true">
                        <i data-feather="info"></i><span class="d-none d-sm-block">Detalhes</span>
                     </a>
                  </li>
               </ul>
            </div>
            <!-- User Sidebar -->
            <div class="col-xl-3 col-lg-4 col-md-4 order-1 order-md-0">
               <!-- User Card -->
               <div id="divcarduser">

               </div>

               <!-- /User Card -->
            </div>
            <!--/ User Sidebar -->

            <!-- User Content -->
            <div class="col-xl-12 col-lg-11 col-md-10 order-0 order-md-1">
               <!-- Project table -->
               <section class="app-user-edit">
                  <div class="card">
                     <div class="card-body">

                        <div class="tab-content">
                           <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                              <form class="form-conta" >
                                 @csrf
                                  <div class="row">
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <label class="form-label" for="titulo">Título</label>
                                              <input type="text" class="form-control dt-full-name" id="titulo"
                                                     placeholder="Título" name="titulo" value="{{ $incident->titulo??'' }}"/>
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <label for="criticidade">Criticidade</label>
                                              <select id="criticidade" name="criticidade" class="form-control select2">
                                                  @foreach($criticality??[] as $critical)
                                                      @if($incident->criticidade_id == $critical['id'])
                                                          <option value="{{ $critical['id'] }}" selected>{{ $critical['nome'] }}</option>
                                                      @else
                                                      <option value="{{ $critical['id'] }}" >{{ $critical['nome'] }}</option>
                                                      @endif
                                                  @endforeach
                                              </select>
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <label for="tipo">Tipo</label>
                                              <select id="tipo" name="tipo" class="form-control select2">
                                                  @foreach($types??[] as $type)
                                                      @if($incident->tipo_id == $type['id'])
                                                          <option value="{{ $type['id'] }}" selected>{{ $type['nome'] }}</option>
                                                      @else
                                                          <option value="{{ $type['id'] }}" >{{ $type['nome'] }}</option>
                                                      @endif
                                                  @endforeach
                                              </select>
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <label for="status">Status</label>
                                          <div class="form-inline">
                                              <input class="form-check-input" type="radio" name="status" id="status1" value="1"
                                                     {{ $incident->status== 1?'checked':'' }}>
                                              <label class="form-check-label" for="status1">
                                                  Ativo
                                              </label>
                                              <input class="form-check-input" type="radio" name="status" id="status0" value="0"
                                                  {{ $incident->status== 0?'checked':'' }}>
                                              <label class="form-check-label" for="status0">
                                                  Inativo
                                              </label>
                                          </div>
                                      </div>
                                      <div class="col-md-12">
                                          <div class="form-group">
                                              <label for="descricao">Descrição</label>
                                              <textarea class="form-control form-control-sm" id="descricao" rows="3" name="descricao">{{ $incident->descricao??'' }}</textarea>
                                          </div>
                                      </div>

                                      <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                          <button type="button" class="btn btn-warning  mb-1 mb-sm-0 mr-0 mr-sm-1"
                                          onclick="window.location.href='/'">Voltar</button>
                                          <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">Salvar Dados</button>
                                          <input type="hidden" value="conta" name="salvarDados">
                                      </div>
                                  </div>
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
               </section>
               <!-- /Project table -->

            </div>
            <!--/ User Content -->
         </div>
      </section>




   </div>
</div>
@endsection
<!-- END: Content-->

@push('css_page')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/app-user.css') }}">
@endpush

@push('js_page')
    <script>
        $(function () {
            'use strict';
            var formConta = $('.form-conta'); //formulario
            var isRtl = $('html').attr('data-textdirection') === 'rtl';
            // Form Conta
            if (formConta.length) {
                formConta.validate({
                    errorClass: 'error',
                    rules: {
                        'titulo': { required: true },
                        'criticidade': { required: true },
                        'tipo': { required: true },
                        'status': { required: true }
                    }
                });

                formConta.on('submit', function (e) {
                    e.preventDefault();
                    var isValid = formConta.valid();
                    var fototipo = $('#fotouser').data('tipo');
                    if (fototipo == 'nova') {
                        var url = document.getElementById("fotouser").getAttribute("src");

                    }

                    if (isValid) {
                        //let serealize = formConta.serializeArray();
                        var serealize = new FormData(formConta[0]);
                        //serealize.push({ name: "file", value: new FormData(formConta[0]) });
                        console.log(serealize);

                        $.ajax({
                            type: "POST",
                            url: '{{ route('edit', ['id' => $incident]) }}',
                            data: serealize,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                toastr['success']('👋'+data.message, 'Sucesso!', {
                                    closeButton: true,
                                    tapToDismiss: false,
                                    rtl: isRtl
                                });
                            }
                        });
                    }
                });
            }
        });

    </script>
@endpush
