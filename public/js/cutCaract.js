function cutCaract(str)
{
    chooseNumber = 30;
    length = str.length;
    result = str.substring(length -chooseNumber, length);
    return result;
}