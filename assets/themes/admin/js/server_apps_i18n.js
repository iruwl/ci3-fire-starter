$(document).ready(function() {
  if ($('#add_db_profile').length > 0) {
    var count_db_profile = $('.db_profile-row').sort(function(a, b) { return +a.id < +b.id })[0].id;
    $('#add_db_profile').click(function() {
        count_db_profile++;
        $('#db_profile_fields').append(
          '<tr class="db_profile-row" id="'+count_db_profile+'">' +
          '  <td><input type="text" name="db_profile['+count_db_profile+'][connection_string]" class="form-control"></td>' +
          '  <td><input type="text" name="db_profile['+count_db_profile+'][keterangan]" class="form-control"></td>' +
          '  <td><button type="button" class="btn btn-default btn-sm btn_remove_db_profile" id="'+count_db_profile+'" tabindex="-1"><span class="glyphicon glyphicon-minus"></span></button></td>' +
          '</tr>'
        );
    });
    $(document).on('click', '.btn_remove_db_profile', function() {
      if(confirm("Yakin akan menghapus item ini?")) {
        var button_id = $(this).attr("id");
        $('.db_profile-row[id=' + button_id + ']').remove();
      }
    });
  }
});
