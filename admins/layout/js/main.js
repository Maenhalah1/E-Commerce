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
    if(allinputs[i].hasAttribute('required')) {
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