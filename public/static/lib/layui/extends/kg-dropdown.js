/**
 Dropdown
 **/

layui.define(['jquery'], function (exports) {

    var MOD_NAME = 'kgDropdown',
        CLASS_NAME = '.kg-dropdown',
        $ = layui.jquery;

    var dropdown = {
        "v": '1.0.0'
    };

    //渲染
    dropdown.render = function (opt) {
        $(CLASS_NAME).each(function (i, elem) {
            var jqelem = $(elem);
            var ulBox = jqelem.children('ul');
            var timer = '';
            ulBox.addClass('layui-anim layui-anim-upbit');
            var event = jqelem.hasClass('dropdown-click') ? 'click' : 'mouseenter';

            jqelem.on(event, function (e) {
                var disabled = jqelem.hasClass('dropdown-disabled');
                if (disabled) {
                    return false
                }
                clearTimeout(timer);
                //FIX 定位
                var overHeight = (jqelem.offset().top + jqelem.height() + ulBox.outerHeight() - $(window).scrollTop()) > $(window).height();
                if (overHeight) {
                    ulBox.css({"top": "auto", "bottom": "100%"});
                } else {
                    ulBox.css({"top": "100%", "bottom": "auto"});
                }
                ulBox.show();
            });
            if (event == 'mouseenter') {
                jqelem.on("mouseleave", function (e) {
                    timer = setTimeout(function () {
                        ulBox.hide();
                    }, 300);
                })
            } else {
                $(document).on("mouseup", function (e) {
                    var userSet_con = jqelem;
                    if (!userSet_con.is(e.target) && userSet_con.has(e.target).length === 0) {
                        ulBox.hide()
                    }
                });
            }
        })
    };

    //自动完成渲染
    dropdown.render();

    //输出接口
    exports(MOD_NAME, dropdown);
});
