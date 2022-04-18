$(function () {
   'use strict';

   var changePicture = $('#change-picture'),
      userAvatar = $('.user-avatar');
   var dtUserHistoryTable = $('.history-list-table');
   var dtUserAditivoTable = $('.aditivo-list-table');
   var tableHistory = false;
   var tableAditivo = false;
   var formConta = $('.form-conta'); //formulario
   var formPessoais = $('.form-pessoais'); //formulario
   var formControle = $('.form-controle'); //formulario
   var formAditivo = $('.form-aditivo'); //formulario
   var formEndereco = $('.form-endereco'); //endereco
   // var isValid = formConta.valid();
   var isRtl = $('html').attr('data-textdirection') === 'rtl';
   var iduser = $('#iduser').val();

   // historyList();
   divUser();
   // AditivoList();

   // change resul
   if ($('#hempresa').val() == '') {
      $('#empresa').val(0); $('#empresa').trigger('change');
   }
   var data = new Date();
   var dataFormatada = data.toLocaleDateString('pt-BR', { timeZone: 'UTC' });
   $('#alteracao').val(dataFormatada); $('#alteracao').trigger('change');
   $('#alteracao2').val(dataFormatada); $('#alteracao2').trigger('change');

   function dataBR(data) {
      //do americano para portugues
      let datef = new Date(data);
      let dataFormatada = datef.toLocaleDateString('pt-BR', { timeZone: 'UTC' });
      return dataFormatada
   }


   $('#fotoUser').on('change', function (e) {
      console.log($('#fotoUser').val());
   });
   // Change user profile picture
   if (changePicture.length) {
      $(changePicture).on('change', function (e) {

         var reader = new FileReader(),
            files = e.target.files;
         reader.onload = function () {
            if (userAvatar.length) {
               userAvatar.attr('src', reader.result);
            }
         };
         reader.readAsDataURL(files[0]);
      });
   }
   function AditivoList() {
      var id_pj = $('#id_pj').val();
      var groupColumn = 2;

      if (tableAditivo) {
         tableAditivo.destroy();
      }
      if (id_pj) {
         if (dtUserAditivoTable.length) {
            var groupingTable = dtUserAditivoTable.DataTable({
               retrieve: true,
               ajax: {
                  url: "/aditivo/" + id_pj, dataSrc: ""
               },
               columns: [
                  { data: 'id' },
                  { data: 'valor_aditivo' },
                  { //formatando a data para pt-br
                     data: function (dados) {
                        if (dados.dt_aditivo) {
                           var datef = new Date(dados.dt_aditivo);
                           var dataFormatada = datef.toLocaleDateString('pt-BR', { timeZone: 'UTC' });
                           return dataFormatada;
                        } else {
                           return null;
                        }

                     }
                  },
                  { data: '' }

               ],
               columnDefs: [
                  {
                     "targets": [0],
                     "visible": false,
                     "searchable": false
                  },
                  {
                     // For Responsive
                     className: 'control',
                     orderable: false,
                     responsivePriority: 2,
                     targets: 0
                  },
                  {
                     // Actions<i data-feather='x-circle'></i>
                     targets: 3,
                     title: '',
                     orderable: false,
                     render: function (data, type, full, meta) {
                        var id = full['id'];
                        var valor = full['valor_aditivo'];
                        return (
                           '<a href="javascript:;" class="item-edit deletar_td_aditivo" data-valor="' + valor + '" data-id="' + id + '" style="color: #f54b20 !important">' +
                           feather.icons['x-circle'].toSvg({ class: 'font-small-4' }) +
                           '</a>'
                        );
                     }
                  }
               ],
               order: [[1, 'asc']],
               dom:
                  '<"d-flex justify-content-between align-items-center mx-0">',
               displayLength: 1000,
               lengthMenu: [7, 10, 25, 50, 75, 100],


               language: {
                  paginate: {
                     // remove previous & next text from pagination
                     previous: '&nbsp;',
                     next: '&nbsp;'
                  }
               }
            });

            // Order by the grouping
            $('.dt-row-grouping tbody').on('click', 'tr.group', function () {
               var currentOrder = table.order()[0];
               if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
                  groupingTable.order([groupColumn, 'desc']).draw();
               } else {
                  groupingTable.order([groupColumn, 'asc']).draw();
               }
            });
         }
      }
      tableAditivo = groupingTable;
   }
   // Datatable - history

   function divUser() {
      $.get('/usuario/getuser/' + iduser, function (retorno) {
         $('#divcarduser').html(retorno);
      });
   }
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
                  toastr['danger']('👋 Histórico comprometido, não pode excluir.', 'Erro!', {
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
                  toastr['success']('👋 Histórico Removido.', 'Sucesso!', {
                     closeButton: true,
                     tapToDismiss: false,
                     rtl: isRtl
                  });

               }
            });
         }
      });
   });
   $(document).on('click', '.deletar_td_aditivo', function () {
      var t = dtUserAditivoTable.DataTable();
      var row = dtUserAditivoTable.DataTable().row($(this).parents('tr')).node();
      var id = $(this).data('id');
      //mensagem de confirmar
      Swal.fire({
         title: 'Remover Aditivo',
         text: $(this).data('valor') + '?',
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
            $.get('/aditivo/delete/' + id, function (retorno) {
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
                  toastr['success']('👋 Aditivo Removido.', 'Sucesso!', {
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
   $(document).on('click', '.btmudar', function () {
      $('#fotouser').data('tipo', 'nova');
      $('#temfoto').val('tem');
   });
   $(document).on('click', '.btreset', function (e) {
      e.preventDefault();
      $('#fotouser').data('tipo', 'avatar');

      var img = document.querySelector("#fotouser");
      img.setAttribute('src', '/app-assets/images/avatars/avatar.png');
      $('#temfoto').val('naotem');
   });
   // Form Conta
   if (formConta.length) {
      formConta.validate({
         errorClass: 'error',
         rules: {
            'fullname': { required: true },
            'email': { required: true },
            'status': { required: true },
            'perfil': { required: true }
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
               url: '/usuario/cadastro',
               data: serealize,
               processData: false,
               contentType: false,
               success: function (data) {
                  console.log(data);
                  console.log('conta');

                  // var divcarhistory = $('#divcarhistory');
                  // divcarhistory.animate({ opacity: 0, marginTop: "100px" }, 500, "easeInQuart", function () {
                  //    //historyList();
                  //    divcarhistory.animate({ opacity: 1, marginTop: "0" }, 500, "easeOutQuart", function () { });
                  // });

                  var divcarduser = $('#divcarduser');
                  divcarduser.animate({ opacity: 0, marginTop: "100px" }, 500, "easeInQuart", function () {
                     console.log('animando');
                     divUser();
                     console.log('trocou');
                     divcarduser.animate({ opacity: 1, marginTop: "0" }, 500, "easeOutQuart", function () { });
                  });


               }
            });
         }
      });
   }
   // Form Pessoal
   if (formPessoais.length) {
      // formPessoais.validate({
      //     errorClass: 'error',
      //     rules: {
      //         'fullname': { required: true },
      //         'email': { required: true },
      //         'status': { required: true },
      //         'perfil': { required: true }
      //     }
      // });

      formPessoais.on('submit', function (e) {
         e.preventDefault();
         var isValid = formPessoais.valid();
         let serealize = formPessoais.serializeArray();

         if (isValid) {
            let serealize = formPessoais.serializeArray();
            console.log(serealize);

            $.ajax({
               type: "POST",
               url: '/usuario/cadastro',
               data: serealize,
               success: function (data) {
                  var divcarduser = $('#divcarduser');
                  divcarduser.animate({
                     opacity: 0,
                     marginTop: "100px"
                  }, 500, "easeInQuart", function () {
                     divUser();
                     divcarduser.animate({
                        opacity: 1,
                        marginTop: "0"
                     }, 500, "easeOutQuart", function () {
                        //historyList();
                     });
                  });
               }
            });
         }
      });
   }
   // Form Controle
   if (formControle.length) {
      // formControle.validate({
      //     errorClass: 'error',
      //     rules: {
      //         'cnpj': { required: true },
      //         'representante': { required: true }
      //     }
      // });

      formControle.on('submit', function (e) {
         e.preventDefault();
         var isValid = formControle.valid();
         if (isValid) {
            let serealize = formControle.serializeArray();
            console.log(serealize);

            $.ajax({
               type: "POST",
               url: '/usuario/cadastro',
               data: serealize,
               success: function (retorno) {
                  console.log(retorno);
                  if (retorno['tipo-pj'] == 'novo') {
                     $('#id_pj').val(retorno['id-pj'])
                     $('.divaditivos').fadeIn(500);
                  }
                  var divcarduser = $('#divcarduser');
                  divcarduser.animate({
                     opacity: 0,
                     marginTop: "100px"
                  }, 500, "easeInQuart", function () {
                     divUser();
                     divcarduser.animate({
                        opacity: 1,
                        marginTop: "0"
                     }, 500, "easeOutQuart", function () {
                       //historyList();
                     });
                  });
               }
            });
         }
      });
   }
   // Form Aditivo
   if (formAditivo.length) {
      formAditivo.validate({
         errorClass: 'error',
         rules: {
            'valor_aditivo': { required: true },
            'dt_aditivo': { required: true }
         }
      });
      formAditivo.on('submit', function (e) {
         e.preventDefault();
         var isValid = formAditivo.valid();
         if (isValid) {
            let serealize = formAditivo.serializeArray();
            $.ajax({
               type: "POST",
               url: '/aditivo/cadastro',
               data: serealize,
               success: function (retorno) {
                  console.log(retorno);
                  $('#valor_aditivo').val('');
                  $('#dt_aditivo').val('');
                  AditivoList();
                  // mensagem info
                  toastr['success']('👋 Aditivo Adicionado.', 'Sucesso!', {
                     closeButton: true,
                     tapToDismiss: false,
                     rtl: isRtl
                  });
               }
            });
         }
      });
   }

   if (formEndereco.length) {

        formEndereco.on('submit', function (e) {
            e.preventDefault();
            var isValid = formEndereco.valid();
            if (isValid) {
                let serealize = formEndereco.serializeArray();
                console.log(serealize);
                $.ajax({
                    type: "POST",
                    url: '/usuario/cadastro',
                    data: serealize,
                    success: function (retorno) {
                        console.log(retorno);
                        $('#valor_aditivo').val('');
                        $('#dt_aditivo').val('');
                        AditivoList();
                        // mensagem info
                        toastr['success']('👋 Aditivo Adicionado.', 'Sucesso!', {
                            closeButton: true,
                            tapToDismiss: false,
                            rtl: isRtl
                        });
                    }
                });
            }
        });
    }

   $('#divCeletista').hide();
   $('#divPrestodor').hide();
   $('.divaditivos').hide();
   if ($('#id_pj').val()) {
      $('.divaditivos').fadeIn(500);
   }
   let comp_regime = $('input[type=radio][name=regime]:checked').val();
   if (comp_regime == 'Prestador de Serviço') {
      $('#divPrestodor').fadeIn(500);
      // $('.divaditivos').fadeIn(500);
      $('#divCeletista').hide();
      if ($('#id_pj').val()) {
         $('.divaditivos').fadeIn(500);
      }
      $('.text-adm').hide();
   }
   else if (comp_regime == 'Celetista') {
      $('#divPrestodor').hide();
      $('#divCeletista').fadeIn(500);
      $('.divaditivos').hide();
      $('.text-adm').show();
   }
   $(document).on('change', 'input[type=radio][name=regime]', function () {
      if (this.value == 'Prestador de Serviço') {
         $('#divPrestodor').fadeIn(500);
         // $('.divaditivos').fadeIn(500);
         $('#divCeletista').hide();
         if ($('#id_pj').val()) {
            $('.divaditivos').fadeIn(500);
         }
      }
      else if (this.value == 'Celetista') {
         $('#divPrestodor').hide();
         $('#divCeletista').fadeIn(500);
         $('.divaditivos').hide();
      }
   });

   $('#valor_aditivo').mask('#.##0,00', { reverse: true });
   $('#salario').mask('#.##0,00', { reverse: true });


   //verificando item custo
   var pefiluser = $('#perfiluser').val();
   $('.text-adm').hide();

   if (pefiluser == 10) {
      if (comp_regime == 'Celetista') {
         $('.text-adm').show();
         custosalario();
      }
   }



   $(document).on('keypress', '#salario', function (e) {
      // e.preventDefault();
      if (comp_regime == 'Celetista') {
         custosalario();
      }
   });

   function custosalario() {
      var custosalario = 0;
      var salario = $('#salario').val();
      let salarioLimpo = parseInt(salario.replace(/[.,]/g, ''));   //210000

      console.log(salarioLimpo);

      if (salarioLimpo > 450000) {
         custosalario = 0;
         custosalario = 90 * salarioLimpo / 100 + salarioLimpo;
         custosalario = parseInt(custosalario);
         console.log('1');
         console.log(custosalario);

      }

      if (salarioLimpo <= 450000) {
         custosalario = 0;
         custosalario = salarioLimpo + salarioLimpo;
         custosalario = parseInt(custosalario);
         console.log('1');
         console.log(custosalario);

      }
      if (salarioLimpo <= 310000) {
         custosalario = 0;
         custosalario = (salarioLimpo * 1.3) + salarioLimpo;
         custosalario = parseInt(custosalario);
         console.log('2');
         console.log(custosalario);
      }
      if (salarioLimpo <= 210000) {
         custosalario = 0;
         custosalario = (salarioLimpo * 1.6) + salarioLimpo;
         custosalario = parseInt(custosalario);
         console.log('3');
         console.log(custosalario);
      }




      $('#custoval').html(custosalario);

      console.log($('#custoval').val());
      $('#custoval').mask('#.##0,00', { reverse: true });

   }



});
