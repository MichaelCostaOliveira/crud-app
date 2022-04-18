/*=========================================================================================
    File Name: form-file-uploader.js
    Description: dropzone
    --------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

Dropzone.autoDiscover = false;

$(function () {
   'use strict';

   var singleFile = $('#dpz-single-file');
   var multipleFiles = $('#dpz-multiple-files');
   var fileUpload = $('#file-upload');
   var buttonSelect = $('#dpz-btn-select-files');
   var limitFiles = $('#dpz-file-limits');
   var acceptFiles = $('#dpz-accept-files');
   var removeThumb = $('#dpz-remove-thumb');
   var removeAllThumbs = $('#dpz-remove-all-thumb');
   var iduser = $('#iduser').val();
   var objs = [];
   // isRtl = $('html').attr('data-textdirection') === 'rtl',

   // Basic example
   singleFile.dropzone({
      paramName: 'file', // The name that will be used to transfer the file
      maxFiles: 1
   });

   // Multiple Files
   multipleFiles.dropzone({
      paramName: 'file', // The name that will be used to transfer the file
      maxFilesize: 0.5, // MB
      clickable: true
   });


   fileUpload.dropzone({
      paramName: 'file', // The name that will be used to transfer the file
      maxFilesize: 10, // MB      
      clickable: true,
      init: function () {
         var totalFiles = 0;
         var completeFiles = 0;
         this.on("addedfile", function (file) {
            totalFiles += 1;
            console.log('addedfile');
            objs.push(this);
         });
         this.on("complete", function (file) {
            completeFiles += 1;
            if (completeFiles === totalFiles) {
               console.log('completo');
               // fileUpload.removeAllFiles( true );
               Dropzone.forElement("#file-upload").removeAllFiles(true);
               getfiles();
            }
         });
      }
   });
   getfiles();
   function getfiles() {
      $.get('/file-upload/getfiles/' + iduser, function (retorno) {
         $('#cardArquivos').html(retorno);
      });
   }

   function down_file(url, name) {
      var a = $("<a>")
         .attr("href", url)
         .attr("download", name)
         .appendTo("body");
      a[0].click();
      a.remove();
   }

   // data-user="{{$value->users_id}}" data-name="{{$valud->name_ref}}" data-tipo="{{$valud->tipo}}" >
   $(document).on('click', '.baixararquivo', function (e) {
      var url = $(this).data('url');
      var name = $(this).data('name');
      down_file('/arquivos/'+url, name);
   });

   $(document).on('click', '.btfechar', function (e) {
      e.preventDefault();
      // var t = dtempresaTable.DataTable();
      // var row = dtempresaTable.DataTable().row($(this).parents('tr')).node();
      var id = $(this).data('id');
      var divpai = $(this);
      var divpai = divpai.closest('.file-manager-item');
      //mensagem de confirmar 
      Swal.fire({
         title: 'Remover o arquivo',
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
            $.get('/file-upload/delete/' + id, function (retorno) {
               if (retorno == 'Erro') {
                  //mensagem
                  toastr['danger']('👋 Arquivo comprometido, não pode excluir.', 'Erro!', {
                     closeButton: true,
                     tapToDismiss: false,
                     rtl: isRtl
                  });
               } else {
                  divpai.css('background-color', '#fe7474 !important');
                  divpai.css('color', '#fff !important');
                  divpai.children().css('color', '#fff !important');
                  divpai.animate({
                     opacity: 0,
                     left: "0",
                     backgroundColor: '#c74747'
                  }, 1000, "linear", function () {
                     divpai.remove();
                  });
                  // mensagem info
               }
            });
         }
      });
   });


   // Use Button To Select Files
   buttonSelect.dropzone({
      clickable: '#select-files' // Define the element that should be used as click trigger to select files.
   });

   // Limit File Size and No. Of Files
   limitFiles.dropzone({
      paramName: 'file', // The name that will be used to transfer the file
      maxFilesize: 0.5, // MB
      maxFiles: 5,
      maxThumbnailFilesize: 1 // MB
   });

   // Accepted Only Files
   acceptFiles.dropzone({
      paramName: 'file', // The name that will be used to transfer the file
      maxFilesize: 1, // MB
      acceptedFiles: 'image/*'
   });

   //Remove Thumbnail
   removeThumb.dropzone({
      paramName: 'file', // The name that will be used to transfer the file
      maxFilesize: 1, // MB
      addRemoveLinks: true,
      dictRemoveFile: ' Trash'
   });

   // Remove All Thumbnails
   removeAllThumbs.dropzone({
      paramName: 'file', // The name that will be used to transfer the file
      maxFilesize: 1, // MB
      init: function () {
         // Using a closure.
         var _this = this;

         // Setup the observer for the button.
         $('#clear-dropzone').on('click', function () {
            // Using "_this" here, because "this" doesn't point to the dropzone anymore
            _this.removeAllFiles();
            // If you want to cancel uploads as well, you
            // could also call _this.removeAllFiles(true);
         });
      }
   });
});
