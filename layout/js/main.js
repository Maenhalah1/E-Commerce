
if (localStorage.getItem('typeLog') == 'signup') {

    $('.right-log .log-box h3 .localReg').addClass('viewed').siblings().removeClass('viewed');
    $('.right-log .log-box form').hide();
    $('.' + $('.right-log .log-box h3 .localReg').data('class')).fadeIn(0);
     $('.left-log img').hide();
    $('.' + $('.right-log .log-box h3 .localReg').data('class') + 'Img').fadeIn(0);
}


 $("select").selectBoxIt({

    autoWidth: false,

     theme: "jqueryui",

     // Uses the jQuery 'fadeIn' effect when opening the drop down
    showEffect: "fadeIn",

    // Sets the jQuery 'fadeIn' effect speed to 400 milleseconds
    showEffectSpeed: 500,

    // Uses the jQuery 'fadeOut' effect when closing the drop down
    hideEffect: "fadeOut",

    // Sets the jQuery 'fadeOut' effect speed to 400 milleseconds
    hideEffectSpeed: 500
 });
var inputs = document.querySelectorAll('.place');
inputs.forEach( ele => {
    ele.addEventListener('focus', function(){
        ele.setAttribute('data-place', ele.getAttribute('placeholder'));
        ele.setAttribute('placeholder', '');
    });
    ele.addEventListener('blur', function(){
        ele.setAttribute('placeholder', ele.getAttribute('data-place'));
        ele.setAttribute('data-place','');
    });
});


var allinputs = document.getElementsByTagName('input');
var i;
for(i =0; i < allinputs.length; i += 1) {
    if(allinputs[i].classList.contains('required')) {
        var star = document.createElement('span');
        star.classList.add('star');
        star.textContent = "*";
        allinputs[i].parentElement.append(star);
    }
}


var show = document.querySelectorAll('.showing');
show.forEach(ele =>{
    var inputpass = ele.parentElement.querySelector(".pass");
    ele.addEventListener("click", function(){
       if(inputpass.classList.contains('hid')) {
            inputpass.removeAttribute('type');
            inputpass.setAttribute('type', 'text');
            inputpass.classList.remove('hid');
            ele.classList.remove("fa-eye");
            ele.classList.add("fa-eye-slash");
           
       }else {
        inputpass.removeAttribute('type');
        inputpass.setAttribute('type', 'password');
        inputpass.classList.add('hid');
        ele.classList.remove("fa-eye-slash");
            ele.classList.add("fa-eye");
       }
    });
});

function checkConfirm(){
    return window.confirm("Are You Sure To Delete This User ?");
}


$('.catg h2').click(function(){
    $(this).next('.view-catg').fadeToggle(300);
}); 

$('.options span').click(function(){
    $(this).addClass('active').siblings('span').removeClass('active');
    if ($(this).data('view') == 'full') {
        $('.catg .view-catg').fadeIn(200);
    }else {
        $('.catg .view-catg').fadeOut(200);
    }

});

$('.toggle-latest').click(function(){
    $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(200);
    if($(this).hasClass('selected')) {
        $(this).html('<i class="fa fa-minus-circle fa-lg"></i>');
    }else {
        $(this).html('<i class="fa fa-plus-circle fa-lg"></i>');
    }

});
$('.right-log .log-box h3 span').click(function(){
        localStorage.setItem('typeLog',$(this).data('class'))
    $(this).addClass('viewed').siblings().removeClass('viewed');
    $('.right-log .log-box form').hide();
    $('.' + $(this).data('class')).fadeIn(500);
     $('.left-log img').hide();
    $('.' + $(this).data('class') + 'Img').fadeIn(500);

});

$('.newItem .mypanel .panel-body .add-form .add-box .live ').keyup(function(){
    
        $('.newItem .mypanel .panel-body .show-product ' + $(this).data("class")).text($(this).val());

}); 

var dropdown = document.querySelector(".upperBar .dropdownuser .username-dropdown");
var dropdownIcon = document.querySelector(".upperBar .dropdownuser .username-dropdown .fa");
var dropdownhead = document.querySelector(".upperBar .dropdownuser .username-dropdown span");
var dropdownimg = document.querySelector(".upperBar .dropdownuser .username-dropdown img");
var dropdownLinks = document.querySelector(".upperBar .dropdownuser .linksdropdown");
var dropdowninLinks = document.querySelectorAll(".upperBar .dropdownuser .linksdropdown a");
dropdown.addEventListener("click", function(){

    dropdown.classList.toggle("showdropdown");
    dropdownLinks.classList.toggle("showlinksdropdown");
    if(dropdownIcon.classList.contains('fa-caret-down')) {
        dropdownIcon.classList.remove("fa-caret-down");
        dropdownIcon.classList.add("fa-caret-left");

    }else {
        dropdownIcon.classList.remove("fa-caret-left");
        dropdownIcon.classList.add("fa-caret-down");
    }

});




document.addEventListener("click", function(e){

        if(e.target != dropdown && e.target != dropdownIcon && e.target != dropdownhead && e.target != dropdownimg){
            if(dropdown.classList.contains("showdropdown")){
                dropdown.classList.remove("showdropdown");
                dropdownLinks.classList.remove("showlinksdropdown");
                dropdownIcon.classList.remove("fa-caret-left");
                dropdownIcon.classList.add("fa-caret-down");
    }
}

});