function register() {

    const login = $('#username').val();
    const password = $('#password').val();
    const rememberme = $('#rememberme').val();
    const rememberme2 = $('#rememberme').val();

      $.ajax({
          type: 'POST',
          url: 'http://localhost:81',
          data: {
              metod: 'ajax',
              PageAjax: 'register',
              var3: rememberme2,
              login: login,
              pass: password,
              rememberme: rememberme },
          success: function(response){
                $('#autorize').html(response);
             },
             dataType:"json"
      });
};

function getLogin(handleLogin) {

    const login = $('#login').val();

    $.ajax({
        type: 'POST',
        url: '/index.php',
        data: { metod: 'ajax', PageAjax: 'checkLogin', login: login },
        dataType: 'json',
        success: function (data) {
            handleLogin(data);
        }
    });
};

function see_more(button) {

    const current_record = $(button).attr('current_record');
    const model = $(button).attr('model');
    const view = $(button).attr('view');
    const method = $(button).attr('method');
    const page = $(button).attr('value');
    const id = $(button).attr('id');
    const editor = $(button).attr('editor');

    $.ajax({
        type: 'POST',
        url: '/index.php',
        data: {
            metod: 'ajax',
            PageAjax: 'seeMore',
            current_record: current_record,
            model: model,
            view: view,
            method: method,
            page: page,
            editor: editor,
        },
        dataType: 'json',
        success: function (data) {

            if (id == 'show_more') {

                $(data).insertAfter(button).hide().fadeIn('slow');
                $(button).remove();
            } else {

                $(button).parent().html(data).hide().slideDown(300);
                $('.' + page).css({
                    'background-color': '#707070',
                    'color' : '#ffffff',
                });
            }
        }
    });
};

function add_basket_one(product) {

    const id = $(product).attr("id");
    const count = $(product).attr("count");
    const uid = $(product).attr("data-product-guid");
    const prim = $(product).attr("prim");

    $.ajax({
        type: 'POST',
        url: '/index.php',
        data: {
            metod: 'ajax',
            PageAjax: 'addGoods',
            uid: uid,
            count: count,
            prim: prim,
            id:id
        },
        dataType:'json',
        success: function (data) {
            $('#product').html(data);
        },
    });
};

function clear_basket_one(idProduct) {

    const id = $(idProduct).attr("id");
    const user = $(idProduct).attr("user");
    const count = $(idProduct).attr("count");
    const uid = $(idProduct).attr("data-product-guid");
    const model = $(idProduct).attr("model");
    const method = $(idProduct).attr("method");
    const view = $(idProduct).attr("view");

      $.ajax({
          type: 'POST',
          url: 'http://localhost:81',
          data: {
              metod: 'ajax',
              PageAjax: 'deleteGood',
              uid: uid,
              count: count,
              id:id,
              user: user,
              view: view,
              method: method,
              model: model,
          },
          success: function (data) {
              $('.insert_basket').html(data);
          },
     dataType:"json"
      });
};

function search_product() {

    const name = $('#search').val();

    $.ajax({
        type: 'POST',
        url: 'http://localhost:81',
        data: {
            metod: 'ajax',
            PageAjax: 'searchProduct',
            name: name,
        },
        dataType: "json"
    }).done(function (data) {
        $('.show_search_result').hide().show('slow').html(data);
        console.log(data);
    });
};







// ---------------------------------- code i dont use ------------------------------------------------

// function show_user(var1) {
//     var user = $(var1).attr("user");
//
//     $.ajax({ type: 'POST', url: 'http://localhost:81', data: { metod: 'ajax', PageAjax: 'showUserProducts', user: user},
//         dataType:"json"
//     }).done(function (data) {
//         $('#new_count').html(data);
//     });
// };

// function f() {
//     var msg = $('#test').serialize();
//
//     $.ajax({
//         type: 'POST',
//         url: '/index.php',
//         data: {
//             metod: 'ajax',
//             PageAjax: 'testAjax',
//             msg: msg,
//             success: function(data) {
//                 $("#result").html(data);
//                 alert(msg);
//             },
//         },
//     });
// };


// function show_user_info(id) {
//     var user = $(id).attr("user");
//
//     $.ajax({ type: 'POST', url: 'http://localhost:81', data: { metod: 'ajax', PageAjax: 'showUserInfo', user: user},
//         dataType:"json"
//     }).done(function (data) {
//         $('#new_count').html(data);
//     });
// };

// function show_article(id) {
//     var editor = $(id).attr("editor");
//     var id_article = $(id).attr("id_article");
//
//     $.ajax({
//         type: 'POST',
//         url: '/index.php',
//         data: {
//             metod: 'ajax',
//             PageAjax: 'showArticle',
//             id_article: id_article,
//             editor: editor
//         },
//         dataType: "json"
//     }).done(function (data) {
//         $('#article').html(data);
//     });
// };

// function edit_article(id) {
//     var id_article = $(id).attr('id_article');
//
//     $.ajax({
//         type: 'POST',
//         url: '/index.php',
//         data: {
//             metod: 'ajax',
//             PageAjax: 'editArticle',
//             id_article: id_article,
//         },
//         dataType: "json"
//     }).done(function (data) {
//         $('#article').html(data);
//     });
// };

// function edit_product(idProduct) {
//     var uid = $(idProduct).attr("data-product-guid");
//
//     $.ajax({ type: 'POST', url: 'http://localhost:81', data: { metod: 'ajax', PageAjax: 'showProduct', uid: uid},
//         dataType:"json"
//     }).done(function (data) {
//         $('#show_product').html(data);
//     });
//
// }
//
// function show_users_with_basket() {
//     $.ajax({ type: 'POST', url: 'http://localhost:81', data: { metod: 'ajax', PageAjax: 'showUsersWithBasket'},
//         dataType:"json"
//     }).done(function (data) {
//         $('#new_count').html(data);
//     });
// };

// function see_additional_goods(var3) {
//  var count = $(var3).attr("count_tovar");
//  var category = $(var3).attr("category");
//  var current_record = $(var3).attr("current_record");
//           $.ajax({ type: 'POST', url: '/index.php', data: { metod: 'ajax', PageAjax: 'seeAdditionalProducts', count: count, category: category, current_record: current_record}, success: function(response){
//                     $('#button_see_additional_goods').after(response);
// 					$('#button_see_additional_goods').remove();
//                  },
// 				 dataType:"json"
//           });
//    };


// function clear_basket() {
//     $.ajax({ type: 'POST', url: 'http://localhost/phpLevel2Version2/public/index.php', data: { metod: 'ajax', PageAjax: 'clear_basket'}, success: function(response){
//             $('#basket').html(response);
//         },
//         dataType:"json"
//     });
// };

// function add_basket(var3) {
//     var var4 = $(var3).attr("data-product-guid");
//     var count = encodeURI(document.getElementById('i2').value);
//     $.ajax({ type: 'POST', url: '/index.php', data: { metod: 'ajax', PageAjax: 'basket', var4: var4, count_goods: count}, success: function(response){
//             $('#basket').html(response);
//         },
//         dataType:"json"
//     });
// };