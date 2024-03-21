
$(document).ready(function() {
    $('.delete').click(function() {
        var id = $(this).attr('meta-id');
        var table = $(this).attr('meta-table');
        var key = $(this).attr('meta-key');
        
        var confirmation = confirm('Are you sure you want to delete this product?');
        
        //console.log(table, key);
        //console.log(id);
        if (confirmation) {
            $.ajax({
                url: '../../delete-item.php',
                type: 'POST',
                data: {table: table, id: id,  key:  key},
                success: function(response) {
                    if (response == 'success') {
                        alert('Product deleted successfully');
                        location.reload();
                    } else {
                        alert('There was an error deleting the product');
                        console.log(response)
                    }
                }
            });
        }
    });

    $('.banner .close').click(function(e){
        $(this).parent().hide();
    });

   
        $('form input, form select, form textarea').on('keyup change', function() {
            var empty = false;
            var form = $(this).closest('form');

            form.find('input, select, textarea').each(function() {
                if ($(this).val() == '') {
                    empty = true;
                }
            });

            if (empty) {
                form.find('button[type="submit"]').attr('disabled', 'disabled');
            } else {
                form.find('button[type="submit"]').removeAttr('disabled');
            }
        });
    

});

