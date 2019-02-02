function changeTextImg(idLabel)
{
    $(idLabel).on('change',function(){
        var fileName = $(this).val();
        $(this).next('.custom-file-label').html( '...' + cutCaract(fileName));
        
    })
}

function cutCaract(str)
{
    chooseNumber = 30;
    length = str.length;
    result = str.substring(length -chooseNumber, length);
    return result;
}