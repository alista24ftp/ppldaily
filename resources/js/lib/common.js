// 导航 切换查询类型 start
$(function () {

    $("#select").click(function (e) {
        $(".select_down").toggle()
        $(document).one("click", function(){
            $(".select_down").hide();
        });
        e.stopPropagation();
    })
    // 经过选项变色，鼠标离开保留颜色
    $(".select_down p").hover(function () {
            $(this).css("background","#fff").siblings("p").css("background","#f6f6f6")
        },function () {
            $(this).css("background","#fff")
    })
    // 点击选项替换内容
    $(".select_down p").click(function () {
        $(".showText").html($(this).html())
        $(".select_down").hide()
        event.stopPropagation();
    })

    //手机点击导航显示菜单
    $(".slideDown").click(function (e) {
        console.log(1223)
        $(".mobileNav ul").toggle()
        $(document).one("click", function(){
            $(".mobileNav ul").hide();
        });
        e.stopPropagation();
    })
    $(".mobileNav ul li").click(function (e) {
        $(".mobileNav ul").hide()
        e.stopPropagation();
    })

})
// 导航 切换查询类型 end