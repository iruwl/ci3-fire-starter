$(document).ready(function() {

    /**
     * Delete
     */
    $('.btn-delete-user').click(function() {
        window.location.href = config.baseURL + "admin/master/users/delete/" + $(this).attr('data-id');
    });

});
