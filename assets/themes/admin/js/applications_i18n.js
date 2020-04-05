$(document).ready(function() {
  if ($('#add_dokumen').length > 0) {
    var today = new Date().toISOString().split('T')[0];
    var count_dokumen = $('.dokumen-row').sort(function(a, b) { return +a.id < +b.id })[0].id;
    $('#add_dokumen').click(function() {
        count_dokumen++;
        $('#dokumen_fields').append(
          '<tr class="dokumen-row" id="'+count_dokumen+'">' +
          '    <td>' +
          '        <div class="input-group">' +
          '            <input type="text" name="dokumen['+count_dokumen+'][filename]" class="form-control" readonly="true" style="background-color: transparent;">' +
          '            <input type="text" name="dokumen['+count_dokumen+'][tanggal]" value="'+today+'" class="form-control" style="display: none;">' +
          '            <input type="text" name="dokumen['+count_dokumen+'][filepath]" class="form-control" style="display: none;">' +
          '            <span class="input-group-btn">' +
          '                <label class="btn btn-default"><span class="glyphicon glyphicon-folder-open"></span>' +
          '                    <input type="file" name="file" onchange="$(\'input[name^=\\\'dokumen['+count_dokumen+'][filename]\\\']\').val(this.files[0].name); upload(this, $(\'input[name^=\\\'dokumen['+count_dokumen+'][filepath]\\\']\'));" style="display: none;">' +
          '                </label>' +
          '            </span>' +
          '        </div>' +
          '    </td>' +
          '    <td><input type="text" name="dokumen['+count_dokumen+'][keterangan]" class="form-control"></td>' +
          '    <td><button type="button" class="btn btn-default btn-sm btn_remove_dokumen" id="0" tabindex="-1"><span class="glyphicon glyphicon-minus"></span></button></td>' +
          '</tr>'
        );
    });
    $(document).on('click', '.btn_remove_dokumen', function() {
      if(confirm("Yakin akan menghapus?")) {
        var button_id = $(this).attr("id");
        $('.dokumen-row[id=' + button_id + ']').remove();
      }
    });
  }
});
