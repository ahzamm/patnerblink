$(window).load(function(){
// ..
var x = Math.floor((Math.random() * 20));
var y = Math.floor((Math.random() * 20));
$('#first').html(x);
$('#second').html(y);
var sum=x+y;
$('#sum').val(sum);
// ..
});
function captcha() {
    var x = Math.floor((Math.random() * 20));
    var y = Math.floor((Math.random() * 20));
    $('#first').html(x);
    $('#second').html(y);
    var sum=x+y;
    $('#sum').val(sum);
}