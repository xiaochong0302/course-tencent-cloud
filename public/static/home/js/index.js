layui.use(['carousel', 'flow'], function () {

    var carousel = layui.carousel;
    var flow = layui.flow;

    carousel.render({
        elem: '#carousel',
        width: '100%',
        height: '336px'
    });

    flow.lazyimg();
});