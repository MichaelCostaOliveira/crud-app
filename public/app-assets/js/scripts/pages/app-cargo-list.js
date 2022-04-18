$(function () {
  'use strict';
  var password = true;
  var row_edit = '';
  var confirmText = $('#confirm-text');
  var dtcargoTable = $('.cargo-list-table'), //id da tabela q esta na div
    newcargoSidebar = $('.new-cargo-modal'), //name do modal
    isRtl = $('html').attr('data-textdirection') === 'rtl',
    newcargoForm = $('.add-new-cargo'); //formula

  // Datatable
  if (dtcargoTable.length) {
    dtcargoTable.DataTable({
      //busca uma rota
      // ajax: assetPath + 'data/cargo-list.json', // JSON file to add data
      ajax:{url:"/cargo/all",dataSrc:""},
      columns: [
        // columns according to JSON
        { data: 'id' },
        { data: 'name' },
        { data: '' }
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          orderable: false,
          responsivePriority: 2,
          targets: 0
        },
        {
          // Actions
          targets: -1,
          title: 'Ação',
          orderable: false,
          render: function (data, type, full, meta) {
            var $id = full['id'],
               $name = full['name'];

            return (
              '<div class="btn-group">' +
              '<a class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">' +
              feather.icons['more-vertical'].toSvg({ class: 'font-small-4' }) +
              '</a>' +
              '<div class="dropdown-menu dropdown-menu-right">' +
                '<a class="dropdown-item">' +feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) +'Detalhes</a>' +
                '<a data-name="'+$name+'" data-id="'+$id+'" class="dropdown-item" data-toggle="modal" data-target="#modals-slide-in" id="editar_td">' + feather.icons['archive'].toSvg({ class: 'font-small-4 mr-50' }) + 'Editar</a>' +
                '<a href="javascript:;" class="dropdown-item delete-record" data-id="'+$id+'" data-name="'+$name+'"  id="deletar_td">' +feather.icons['trash-2'].toSvg({ class: 'font-small-4 mr-50' }) +'Deletar</a></div>' +
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
      language: {
        paginate: {
          // remove previous & next text from pagination
          previous: '&nbsp;',
          next: '&nbsp;'
        }
      },
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
          text: feather.icons['plus'].toSvg({ class: 'mr-50 font-small-4 ' }) + 'Novo Cargo',
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

      language: {
        "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
        paginate: {
          // remove previous & next text from pagination
          previous: '&nbsp;',
          next: '&nbsp;'
        }
      },
      initComplete: function () {
       // Adding role filter once table initialized
       this.api()
       .columns(1)
       .every(function () {
         var column = this;
         var select = $(
           '<select id="cargoRole" class="form-control select2 "><option value=""> name </option></select>'
         )
           .appendTo('.cargo_role')
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
      }
    });
    $('div.head-label').html('<h6 class="mb-0">Listando todas as cargos</h6>');
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
  if (newcargoForm.length) {
    newcargoForm.validate({
      errorClass: 'error',
      rules: {
        'name': {
          required: true
        }
      }
  });

  newcargoForm.on('submit', function (e) {
      var isValid = newcargoForm.valid();
      e.preventDefault();
      if (isValid) {
        let serealize = newcargoForm.serializeArray();
        console.log(serealize);
        $.ajax({
            type: "POST",
            url: '/cargo/cadastro',
            data: serealize,
            success: function(data)
            {
              var result = data.split(',');
              if(result[0] == 'Erro'){}else{
                if(data == 'editado'){
                  editarlinha(serealize, data);
                }else{
                  addnovalinha(serealize, data);
                }
                newcargoSidebar.modal('hide');
              }
            }
        });
      }
    });
  }
  function editarlinha(serealize, data){
    $(row_edit).addClass( 'alteraressatr');
    //  var rowData = dtcargoTable.DataTable().row($('.alteraressatr')).data();  //mostra todos os dados dessa tr;
    dtcargoTable.DataTable().row($('.alteraressatr')).data({
         "id":   serealize[1]['value'],
       "name":   serealize[2]['value'],
           "": ""
    }).draw();

    $(row_edit).css( 'background-color', '#749efe' );
    $(row_edit).css( 'color', '#fff' );
    $(row_edit).animate({
        color: "#555",
        backgroundColor: 'transparent'
        }, 1000, "linear");
    $(row_edit).removeClass( 'alteraressatr');
    //mensagem
    toastr['success']('👋 Arquivo alterado.', 'Sucesso!', {
      closeButton: true,
      tapToDismiss: false,
      rtl: isRtl
    });
  }
  function addnovalinha(serealize, data){
    var t = dtcargoTable.DataTable();
    var rowNode = t
    .row.add( {
      "id":     data,
      "name":   serealize[2]['value'],
      "": ""
    }).draw().node();

    $( rowNode  ).css( 'opacity', '0' );
    $( rowNode  ).css( 'background-color', '#71c754' );
    $( rowNode).animate({
      opacity: 1,
      left: "0",
      backgroundColor: '#fff'
    }, 1000, "linear");
  }
  $(document).on('click','.create-new', function(){
    $("#senha").prop('required',true);
 $(".ediadi").text('Adicionar');
    $("#senhalabel").text('Senha');
    $('#id_geral').val('');
    $('#name').val('');
  });
  $(document).on('click','#editar_td', function(){
    $("#senha").prop('required',false);
 $(".ediadi").text('Editar');
    $("#senhalabel").text('Nova Senha');

    $('#id_geral').val($(this).data('id'))
    $('#name').val($(this).data('name'));
    row_edit = dtcargoTable.DataTable().row( $(this).parents('tr')).node();
  });
  $(document).on('click','#deletar_td', function(){
    var t = dtcargoTable.DataTable();
    var row = dtcargoTable.DataTable().row( $(this).parents('tr') ).node();
    var id = $(this).data('id');
    //mensagem de confirmar
    Swal.fire({
      title: 'Remover o Cargo',
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
        $.get('/cargo/delete/'+id, function(retorno){
          if(retorno == 'Erro'){
              //mensagem
              toastr['danger']('👋 Arquivo comprometido, não pode excluir.', 'Erro!', {
                closeButton: true,
                tapToDismiss: false,
                rtl: isRtl
              });
          }else{
            //animação de saida
            $(row).css( 'background-color', '#fe7474' );
            $(row).css( 'color', '#fff' );
            $(row).animate({
                opacity: 0,
                left: "0",
                backgroundColor: '#c74747'
                }, 1000, "linear", function() {
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
