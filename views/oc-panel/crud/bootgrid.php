var grid = $("#grid-data-api").bootgrid({
    ajax: true,
    url: "<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'ajax'))?>",
    rowCount: [10,25,50,100],
    searchSettings: {
        delay: 500,
        characters: 3
    },    
    formatters: {
        "commands": function(column, row)
        {
            edit_button = "<?if ($controller->allowed_crud_action('update')):?><a href=\"<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'update'))?>/"+ row.<?=$element->primary_key()?> +"\" class=\"btn btn-xs btn-primary_key ajax-load command-edit\" data-row-id=\"" + row.<?=$element->primary_key()?> + "\"><span class=\"fa fa-pencil\"></span></a><?endif?>";
            dele_button = "<?if ($controller->allowed_crud_action('delete')):?><a href=\"<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'delete'))?>/"+ row.<?=$element->primary_key()?> +"\" class=\"btn btn-xs btn-danger command-delete\" data-row-id=\"" + row.<?=$element->primary_key()?> + "\" title=\"<?=__('Are you sure you want to delete?')?>\" data-btnOkLabel=\"<?=__('Yes, definitely!')?>\" data-btnCancelLabel=\"<?=__('No way!')?>\"><span class=\"fa fa-trash-o\"></span></a><?endif?>";

            return edit_button+dele_button;    
        }
    }
})
.on("loaded.rs.jquery.bootgrid", function()
{
    /* Executes after data is loaded and rendered */
    grid.find(".command-delete").on("click", function(event)
    {
        var href = $(this).attr('href');
        var title = $(this).attr('title');
        var text = $(this).data('text');
        var id = $(this).data('row-id');
        var confirmButtonText = $(this).data('btnoklabel');
        var cancelButtonText = $(this).data('btncancellabel');
        event.preventDefault();
        swal({
            title: title,
            text: text,
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: confirmButtonText,
            cancelButtonText: cancelButtonText,
            allowOutsideClick: true,
        },
        function(){
            $.ajax({ url: href,
                }).done(function ( data ) {
                    $("#grid-data-api").bootgrid("reload");
            });
        });
    });
});