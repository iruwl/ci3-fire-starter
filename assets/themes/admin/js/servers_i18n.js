$(document).ready(function() {
  if ($('#add_port').length > 0) {
    var count_port = $('.port-row').sort(function(a, b) { return +a.id < +b.id })[0].id;
    $('#add_port').click(function() {
        count_port++;
        $('#port_fields').append(
          '<tr class="port-row" id="'+count_port+'">' +
          '  <td><input type="text" name="ports['+count_port+'][port]" class="form-control"></td>' +
          '  <td><input type="text" name="ports['+count_port+'][keterangan]" class="form-control"></td>' +
          '  <td><button type="button" class="btn btn-default btn-sm btn_remove_port" id="'+count_port+'" tabindex="-1"><span class="glyphicon glyphicon-minus"></span></button></td>' +
          '</tr>'
        );
    });
    $(document).on('click', '.btn_remove_port', function() {
      if(confirm("Yakin akan menghapus item ini?")) {
        var button_id = $(this).attr("id");
        $('.port-row[id=' + button_id + ']').remove();
      }
    });
  }

  if ($('#add_network').length > 0) {
    var count_network = $('.network-row').sort(function(a, b) { return +a.id < +b.id })[0].id;
    $('#add_network').click(function() {
        count_network++;
        $('#network_fields').append(
          '<tr class="network-row" id="'+count_network+'">' +
          '  <td><input type="text" name="networks['+count_network+'][interface]" class="form-control"></td>' +
          '  <td><input type="text" name="networks['+count_network+'][ip]" class="form-control" pattern="((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}$" title="IP Address"></td>' +
          '  <td><input type="text" name="networks['+count_network+'][keterangan]" class="form-control"></td>' +
          '  <td><button type="button" class="btn btn-default btn-sm btn_remove_network" id="'+count_network+'" tabindex="-1"><span class="glyphicon glyphicon-minus"></span></button></td>' +
          '</tr>'
        );
    });
    $(document).on('click', '.btn_remove_network', function() {
      if(confirm("Yakin akan menghapus item ini?")) {
        var button_id = $(this).attr("id");
        $('.network-row[id=' + button_id + ']').remove();
      }
    });
  }

  if ($('#add_user').length > 0) {
    var count_user = $('.user-row').sort(function(a, b) { return +a.id < +b.id })[0].id;
    $('#add_user').click(function() {
        count_user++;
        $('#user_fields').append(
          '<tr class="user-row" id="'+count_user+'">' +
          '  <td><input type="text" name="users['+count_user+'][user]" class="form-control"></td>' +
          '  <td><input type="text" name="users['+count_user+'][password]" class="form-control"></td>' +
          '  <td><button type="button" class="btn btn-default btn-sm btn_remove_user" id="'+count_user+'" tabindex="-1"><span class="glyphicon glyphicon-minus"></span></button></td>' +
          '</tr>'
        );
    });
    $(document).on('click', '.btn_remove_user', function() {
      if(confirm("Yakin akan menghapus item ini?")) {
        var button_id = $(this).attr("id");
        $('.user-row[id=' + button_id + ']').remove();
      }
    });
  }
});
