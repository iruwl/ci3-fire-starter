

$(document).ready(function() {

  $('#klik').click(function() {


        // BootstrapDialog.show({
        //     title: 'More dialog sizes',
        //     message: 'Hi Apple!',
        //     buttons: [{
        //         label: 'Normal',
        //         action: function(dialog){
        //             dialog.setTitle('Normal');
        //             dialog.setSize(BootstrapDialog.SIZE_NORMAL);
        //         }
        //     }, {
        //         label: 'Small',
        //         action: function(dialog){
        //             dialog.setTitle('Small');
        //             dialog.setSize(BootstrapDialog.SIZE_SMALL);
        //         }
        //     }, {
        //         label: 'Wide',
        //         action: function(dialog){
        //             dialog.setTitle('Wide');
        //             dialog.setSize(BootstrapDialog.SIZE_WIDE);
        //         }
        //     }, {
        //         label: 'Large',
        //         action: function(dialog){
        //             dialog.setTitle('Large');
        //             dialog.setSize(BootstrapDialog.SIZE_LARGE);
        //         }
        //     }]
        // });



    BootstrapDialog.show({
      size: BootstrapDialog.SIZE_WIDE,
      animate: false,
      title: 'Say-hello dialog',
      message: $('<div></div>').load('http://gawean.local/project1/admin/master/clients/add_dialog')
    });

        // BootstrapDialog.show({
        //     message: function(dialog) {
        //         var $message = $('<div></div>');
        //         var pageToLoad = dialog.getData('pageToLoad');
        //         $message.load(pageToLoad);

        //         return $message;
        //     },
        //     data: {
        //         'pageToLoad': 'http://gawean.local/project1/admin/master/clients/add_dialog'
        //     }
        // });
  });

  if ($('#add_contact').length > 0) {
    var today = new Date().toISOString().split('T')[0];
    var count_contact = $('.contact-row').sort(function(a, b) { return +a.id < +b.id })[0].id;
    $('#add_contact').click(function() {
        count_contact++;
        $('#contact_fields').append(
          '<tr class="contact-row" id="'+count_contact+'">' +
          '    <td>' +
          '      <select name="contacts['+count_contact+']" class="form-control">' +
                    $('select[name="select_contacts"]').clone().attr('name', 'newOptions').prop('innerHTML') +
          '      </select>' +
          '    </td>' +
          '    <td><input type="text" class="form-control" readonly="readonly"></td>' +
          '    <td><button type="button" class="btn btn-default btn-sm btn_remove_contact" id="'+count_contact+'" tabindex="-1"><span class="glyphicon glyphicon-minus"></span></button></td>' +
          '</tr>'
        );
        $('select[name="contacts['+count_contact+']"]').select2({
            placeholder: placeholder,
            width: null,
        });
    });
    $(document).on('click', '.btn_remove_contact', function() {
      if(confirm("Yakin akan menghapus item ini?")) {
        var button_id = $(this).attr("id");
        $('.contact-row[id=' + button_id + ']').remove();
      }
    });
  }
});
