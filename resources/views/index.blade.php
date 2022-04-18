
@extends('layout.app', [
    'elementActive' => 'Incidentes'
])
@section('content')
<!-- Modal novo usuário-->
<div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
    <div class="modal-dialog">
        <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            <form class="add-new-user  pt-0">
                @csrf
                <input type="hidden" value="" name="id_geral" id="id_geral">

                <div class="modal-header mb-1">
                    <h5 class="modal-title" id="exampleModalLabel"><span class="ediadi">Adicionar</span> Incidente</h5>
                </div>
                <div class="modal-body flex-grow-1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="titulo">Título</label>
                                <input type="text" class="form-control dt-full-name" id="titulo" placeholder="Nome do Usuário" name="titulo" aria-label="John Doe" aria-describedby="fullname2" />
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" id="email" class="form-control dt-email" placeholder="usuario@example.com" aria-label="usuario@example.com" aria-describedby="email2" name="email" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label" for="status">Status</label>
                                <select id="status" name="status" class="form-control select2" >
                                    <option value="1">Ativo</option>
                                    <option value="2">Inativo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mr-1 data-submit add waves-effect"><i data-feather='check-circle'></i> Salvar</button>
                    <button type="reset" class="btn btn-outline-secondary waves-effect" data-dismiss="modal"> <i data-feather='x'></i> Cancelar</button>
                    <a href="" class="btn btn-outline-primary waves-effect btdetalhe" style="margin-left: 10px;"> <i data-feather='archive'></i> Detalhes</a>



                </div>
            </form>
        </div>

    </div>
</div>
<!-- Modal Fim-->
<style>
    h4{ margin-left: 18px;}
    .card-header{ margin-left: 2px;}
</style>


<div class="content-wrapper" data-aos=fade-left data-aos-delay=0>

    <div class="content-header row">
        <h4>  Gerenciar Incidentes</h4>
    </div>
    <div class="card">
        <h5 class="card-header">Filtro da tabela</h5>

        <div class="d-flex justify-content-between align-items-center mx-50 row pt-0 pb-2">
            <div class="col user_role"></div>
            <div class="col user_plan"></div>
        </div>
    </div>
    <div class="content-body">
        <section class="app-user-list">
            <div class="card" >
                <div class="col">
                    <table class=" table-responsive-lg user-list-table dt-complex-header table table-bordered dataTable no-footer" id="DataTables_Table_1" role="grid" aria-describedby="DataTables_Table_1_info" style="width: 1444px;">

                    <!-- <table class="user-list-table table table-sm table-responsive-lg" style=" display: block;    width: 100%;    overflow-x: auto;    -webkit-overflow-scrolling: touch; " > -->
                        <thead class="thead-light" style="width: 100%">
                            <tr>
                                <th style="width: 45px"></th>
                                <th style="width: 350px">Título</th>
                                <th style="width: 150px">Tipo</th>
                                <th style="width: 150px">Criticidade</th>
                                <th style="width: 150px">Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- list section end -->
        </section>
        <!-- users list ends -->
    </div>
</div>
@endsection


@push('css_page')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/app-user.css') }}">
@endpush

@push('js_page')
    <script>
        $(function () {
            'use strict';
            var password = true;
            var row_edit = '';
            var confirmText = $('#confirm-text');
            var dtUserTable = $('.user-list-table');
            var dtUserHistoryTable = $('.history-list-table'),//id da tabela q esta na div
                newUserSidebar = $('.new-user-modal'), //nome do modal
                isRtl = $('html').attr('data-textdirection') === 'rtl',
                newUserForm = $('.add-new-user'); //formula
            var tableHistory = false;
            var tableUser = false;
            datauser();

            flatpickr('.flatpickr-basic', {
                "dateFormat": 'd/m/Y' // locale for this instance only
            });
            // Datatable - user
            function datauser() {
                if (tableUser) {
                    tableUser.destroy();
                    $('.user_role').html('');
                    $('.user_plan').html('');
                }
                if (dtUserTable.length) {
                    var groupingTable = dtUserTable.DataTable({
                        retrieve: true,
                        //busca uma rota
                        // ajax: assetPath + 'data/user-list.json', // JSON file to add data
                        ajax: { url: "{{ route('spa_all') }}", dataSrc: "" },
                        columns: [
                            // columns according to JSON

                            { data: 'id' },
                            // { data: 'foto' }, //<img src="../../../app-assets/images/icons/angular.svg" class="mr-75" height="20" width="20" alt="Angular" />
                            { data: 'titulo' },
                            { data: function (dados) {
                                    if (dados.insidetes_tipos == null) {
                                        return '';
                                    }else{
                                        return dados.insidetes_tipos.nome;
                                    }
                                }
                            },

                            { //format perfil
                                data: function (dados) {
                                    if (dados.insidetes_criticidade == null) {
                                        return '';
                                    }else{
                                        return dados.insidetes_criticidade.nome;
                                    }
                                }
                            },
                            {
                                data: function (dados) {
                                    if (dados.status == 0) { return '<span class="badge bg-light-danger">Inativo</span>'; }
                                    if (dados.status == 1) { return '<span class="badge bg-light-success">Ativo</span>'; }
                                }
                            }
                        ],
                        columnDefs: [
                            // {
                            //     "targets": [ 0 ],
                            //     "visible": false,
                            //     "searchable": false
                            // },
                            {
                                // para responsividade
                                className: 'control',
                                orderable: false,
                                responsivePriority: 2,
                                targets: 0
                            },
                            {
                                // Actions
                                targets: 0,
                                title: 'Ação',
                                orderable: false,
                                render: function (data, type, full, meta) {
                                    var $id = full['id'],
                                        $titulo = full['titulo']


                                    return (
                                        '<div class="btn-group">' +
                                        '<a class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">' +
                                        feather.icons['more-vertical'].toSvg({ class: 'font-small-4' }) +
                                        '</a>' +
                                        '<div class="dropdown-menu dropdown-menu-right">' +
                                        '<a class="dropdown-item" href="usuario/detalhes/' + $id + '">' + feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) + 'Detalhes</a>' +
                                        '<a href="javascript:;" class="dropdown-item delete-record" data-id="' + $id + '" data-titulo="' + $titulo + '"  id="deletar_td">' + feather.icons['trash-2'].toSvg({ class: 'font-small-4 mr-50' }) + 'Deletar</a></div>' +
                                        '</div>' +
                                        '</div>'
                                    );
                                }
                            }
                        ],
                        order: [[1, 'asc']],
                        dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                        displayLength: 10,
                        lengthMenu: [10, 25, 50, 75, 100],
                        language: linguagem,
                        // Buttons with Dropdown
                        buttons: [
                            {
                                extend: 'collection',
                                className: 'btn btn-outline-secondary dropdown-toggle mr-2 waves-effect',
                                text: feather.icons['share'].toSvg({ class: 'font-small-4 mr-50 ' }) + 'Export',
                                buttons: [
                                    {
                                        extend: 'print',
                                        text: feather.icons['printer'].toSvg({ class: 'font-small-4 mr-50' }) + 'Print',
                                        className: 'dropdown-item',
                                        exportOptions: { columns: [0, 1, 2] }
                                    },
                                    {
                                        extend: 'csv',
                                        text: feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) + 'Csv',
                                        className: 'dropdown-item',
                                        exportOptions: { columns: [0, 1, 2] }
                                    },
                                    {
                                        extend: 'excel',
                                        text: feather.icons['file'].toSvg({ class: 'font-small-4 mr-50' }) + 'Excel',
                                        className: 'dropdown-item',
                                        exportOptions: { columns: [0, 1, 2] }
                                    },
                                    {
                                        extend: 'copy',
                                        text: feather.icons['copy'].toSvg({ class: 'font-small-4 mr-50' }) + 'Copy',
                                        className: 'dropdown-item',
                                        exportOptions: { columns: [0, 1, 2] }
                                    }
                                ],
                                init: function (api, node, config) {
                                    $(node).removeClass('btn-secondary');
                                    $(node).parent().removeClass('btn-group');
                                    setTimeout(function () {
                                        $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                                    }, 50);
                                }
                            },
                            {
                                text: feather.icons['plus'].toSvg({ class: 'mr-50 font-small-4 ' }) + 'Novo incidente',
                                className: 'create-new btn btn-primary waves-effect',
                                attr: {
                                    'data-toggle': 'modal',
                                    'data-target': '#modals-slide-in'
                                },
                                init: function (api, node, config) {
                                    $(node).removeClass('btn-secondary');
                                }
                            }
                        ],
                        initComplete: function () {
                            // Adding role filter once table initialized
                            this.api()
                                .columns(2)
                                .every(function () {
                                    var column = this;
                                    var select = $(
                                        '<select id="UserRole" class="form-control select2 "><option value=""> Tipo </option></select>'
                                    )
                                        .appendTo('.user_role')
                                        .on('change', function () {
                                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                                        });

                                    column
                                        .data()
                                        .unique()
                                        .sort()
                                        .each(function (d, j) {
                                            select.append('<option value="' + d + '" class="text-capitalize">' + d + '</option>');
                                        });
                                });
                            // Adding plan filter once table initialized
                            this.api()
                                .columns(3)
                                .every(function () {
                                    var column = this;
                                    var select = $(
                                        '<select id="UserPlan" class="form-control select2"><option value=""> Criticidade </option></select>'
                                    )
                                        .appendTo('.user_plan')
                                        .on('change', function () {
                                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                                        });
                                    column
                                        .data()
                                        .unique()
                                        .sort()
                                        .each(function (d, j) {
                                            select.append('<option value="' + d + '" class="text-capitalize">' + d + '</option>');
                                        });
                                });
                            // Adding status filter once table initialized

                        }
                    });
                    $('div.head-label').html('<h6 class="mb-0">Todos os Incidentes</h6>');
                }
                tableUser = groupingTable;
            }
            function dataBR(data) {
                //do americano para portugues
                let datef = new Date(data);
                let dataFormatada = datef.toLocaleDateString('pt-BR', { timeZone: 'UTC' });
                return dataFormatada
            }
            function dataUS(data) {
                //do portugues para o americano
                let dataFormatada = data.split('/').reverse().join('-');
                return dataFormatada
            }
            // Check Validity
            function checkValidity(el) {
                if (el.validate().checkForm()) {
                    submitBtn.attr('disabled', false);
                } else {
                    submitBtn.attr('disabled', true);
                }
            }
            // Form Validation
            if (newUserForm.length) {
                newUserForm.validate({
                    errorClass: 'error',
                    rules: {
                        'titulo': { required: true },
                        'email': { required: true },
                        'status': { required: true }
                    }
                });

                newUserForm.on('submit', function (e) {
                    var isValid = newUserForm.valid();
                    e.preventDefault();
                    if (isValid) {
                        let serealize = newUserForm.serializeArray();
                        $.ajax({
                            type: "POST",
                            url: '/usuario/cadastro',
                            data: serealize,
                            success: function (data) {
                                if(data['tipo-geral'] == 'novo'){
                                    window.location.href = "/usuario/detalhes/"+data['id-geral'];
                                }
                                if(data['tipo-geral'] == 'editado'){
                                    editarlinha(serealize, data);
                                    newUserSidebar.modal('hide');
                                }
                            }
                        });
                    }
                });
            }
            function editarlinha(serealize, data) {
                datauser();
                //mensagem
                toastr['success']('👋 Arquivo alterado.', 'Sucesso!', {
                    closeButton: true,
                    tapToDismiss: false,
                    rtl: isRtl
                });
            }
            function addnovalinha(serealize, data) {
                console.log(serealize);
                var t = dtUserTable.DataTable();
                var rowNode = t
                    .row.add({
                        "id": data,
                        "email": serealize[5]['value'],
                        "name": serealize[2]['value'],
                        "perfil": serealize[4]['value'],
                        "status": serealize[3]['value'],
                        "": ""
                    }).draw().node();

                $(rowNode).css('opacity', '0');
                $(rowNode).css('background-color', '#71c754');
                $(rowNode).animate({
                    opacity: 1,
                    left: "0",
                    backgroundColor: '#fff'
                }, 1000, "linear");
            }
            $(document).on('click', '.create-new', function () {
                $("#senha").prop('required', true);
                $(".ediadi").text('Adicionar');
                $("#senhalabel").text('Senha');
                $(".btdetalhe").hide();

                $('#id_geral').val('');
                $('#fullname').val('');
                $('#email').val('');
                $('#salario').val('');
                $('#admissao').val('');

                $('#alocacao').val(0);
                $('#alocacao').trigger('change');

                $('#tarifa').val(0);
                $('#tarifa').trigger('change');

                $('#supervisao').val(0);
                $('#supervisao').trigger('change');

                $('#setor').val(0);
                $('#setor').trigger('change');

                $('#frente').val(0);
                $('#frente').trigger('change');

                $('#cargo').val(0);
                $('#cargo').trigger('change');

                $('#regime').val(0);
                $('#regime').trigger('change');

                $('#empresa').val(0);
                $('#empresa').trigger('change');

            });
            $(document).on('click', '#deletar_td', function () {
                var t = dtUserTable.DataTable();
                var row = dtUserTable.DataTable().row($(this).parents('tr')).node();
                var id = $(this).data('id');
                //mensagem de confirmar
                Swal.fire({
                    title: 'Remover o Incidente',
                    text: $(this).data('titulo') + '?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, pode deletar!',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ml-1'
                    },
                    buttonsStyling: false
                }).then(function (result) {
                    if (result.value) {
                        $.get('/usuario/delete/' + id, function (retorno) {
                            if (retorno == 'Erro') {
                                //mensagem
                                toastr['danger']('👋 Arquivo comprometido, não pode excluir.', 'Erro!', {
                                    closeButton: true,
                                    tapToDismiss: false,
                                    rtl: isRtl
                                });
                            } else {
                                //animação de saida
                                $(row).css('background-color', '#fe7474');
                                $(row).css('color', '#fff');
                                $(row).animate({
                                    opacity: 0,
                                    left: "0",
                                    backgroundColor: '#c74747'
                                }, 1000, "linear", function () {
                                    var linha = $(this).closest('tr');
                                    t.row(linha).remove().draw()
                                });
                                // mensagem info
                                toastr['success']('👋 Arquivo Removido.', 'Sucesso!', {
                                    closeButton: true,
                                    tapToDismiss: false,
                                    rtl: isRtl
                                });

                            }
                        });
                    }
                });
            });
            $(document).on('click', '.deletar_td_history', function () {
                var t = dtUserHistoryTable.DataTable();
                var row = dtUserHistoryTable.DataTable().row($(this).parents('tr')).node();
                var id = $(this).data('id');
                //mensagem de confirmar
                Swal.fire({
                    title: 'Remover do Histórico',
                    text: $(this).data('name') + '?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, pode deletar!',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ml-1'
                    },
                    buttonsStyling: false
                }).then(function (result) {
                    if (result.value) {
                        $.get('/historico/delete/' + id, function (retorno) {
                            if (retorno == 'Erro') {
                                //mensagem
                                toastr['danger']('👋 Arquivo comprometido, não pode excluir.', 'Erro!', {
                                    closeButton: true,
                                    tapToDismiss: false,
                                    rtl: isRtl
                                });
                            } else {
                                //animação de saida
                                $(row).css('background-color', '#fe7474');
                                $(row).css('color', '#fff');
                                $(row).animate({
                                    opacity: 0,
                                    left: "0",
                                    backgroundColor: '#c74747'
                                }, 1000, "linear", function () {
                                    var linha = $(this).closest('tr');
                                    t.row(linha).remove().draw()
                                });
                                // mensagem info
                                toastr['success']('👋 Arquivo Removido.', 'Sucesso!', {
                                    closeButton: true,
                                    tapToDismiss: false,
                                    rtl: isRtl
                                });

                            }
                        });
                    }
                });
            });
            // To initialize tooltip with body container
            $('body').tooltip({
                selector: '[data-toggle="tooltip"]',
                container: 'body'
            });
        });
    </script>
@endpush



