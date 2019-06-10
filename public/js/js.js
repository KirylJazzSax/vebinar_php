$(document).ready(function () {

    if ($('#status').val() == 'on_sale') {
        $('#for_sale').slideDown('slow');
    }

    $('#status').change(function (e) {
        if ($('#status').val() == 'on_sale') {
            $('#for_sale').slideDown('slow');
        } else {
            $('#for_sale').slideUp('slow');
        }

    });

    $('.menu_content_1').hover(
        function (e) {
            $(this).children('ul').slideDown(800);
        },
        function (e) {
            $(this).children('ul').slideUp();
        });

    $('#pass2').blur(function (e) {
        if ($('#pass').val() != $('#pass2').val()) {
            $('.error_password').css('color', 'red').fadeIn('slow');
        } else {
            $('.error_password').fadeOut('slow');
        }
    });

    $('#login').blur(function (e) {

        getLogin(function (data) {

            if (data != '') {
                $('.error_login').css('color', 'red').fadeIn('slow');
            } else {
                $('.error_login').fadeOut('slow');
            }
        });

    });

    $('#register').submit(function (e) {

        getLogin(function (data) {

            if (data != '') {
                $('.error_login').css('color', 'red').fadeIn('slow');
            } else {
                $('.error_login').fadeOut('slow');
            }
        });

        if ($('#pass').val() != $('#pass2').val()) {
            $('.error_password').css('color', 'red').fadeIn('slow');
        } else {
            $('.error_password').fadeOut('slow');
            return true;
        }
        return false;
    });

    $('#search').keypress(function (e) {
        search_product();
    });

    $(document).on('click', '#show_more', function (e) {
        see_more(this);
    });

    $('#show_more').click(function (e) {
        e.stopPropagation();
        see_more(this);
    });

    $('#show_page').click(function (e) {
        e.stopPropagation();
        see_more(this);
    });


});