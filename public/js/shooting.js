function getShooting(id)
{
    $.ajax({
        method: "GET",
        url: "shootings/" + id,
    }).done(function(data) 
    {
        $('#img-shooting').html('<img class="mb-3" src="'+ data.image +'" alt="img">');
        $('#type').text(data.type);
        $('#title-shooting').text(data.title);
        $('#description-shooting').text(data.description);
        $('#address-shooting').text(data.address);
        $('.hide').removeClass('hide');
    });
}