layui.use(['jquery'], function () {

    var $ = layui.jquery;

    var myChart = echarts.init($('#chart')[0]);

    var xAxisName = $('input[name=x_axis_name]').val();
    var yAxisName = $('input[name=y_axis_name]').val();
    var data = JSON.parse($('textarea[name=data]').val());

    var subtext = [
        `当前月：${formatValue(data.curr_month_value)}`,
        `同比月：${formatValue(data.yoy_month_value)}`,
        `环比月：${formatValue(data.prev_month_value)}`,
        `同比：${formatRate(data.yoy_growth_rate)}`,
        `环比：${formatRate(data.mom_growth_rate)}`,
    ].join('，');

    var option = {
        legend: {
            data: ['当前月', '同比月', '环比月'],
        },
        title: {
            subtext: subtext,
            left: 'center',
            top: 30,
        },
        grid: {
            top: 100,
        },
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'line',
            },
        },
        dataset: {
            source: data.items,
        },
        xAxis: {type: 'category', 'name': xAxisName},
        yAxis: {type: 'value', 'name': yAxisName},
        series: [
            {
                name: '当前月',
                type: 'line',
                smooth: true,
                symbol: 'circle',
                symbolSize: 6,
                lineStyle: {
                    width: 2,
                    color: '#1890FF',
                },
                itemStyle: {
                    color: '#1890FF',
                    borderWidth: 2,
                    borderColor: '#fff',
                },
                encode: {
                    x: 'date',
                    y: data.curr_month_key,
                },
                areaStyle: {
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                        {offset: 0, color: 'rgba(24, 144, 255, 0.6)'},
                        {offset: 1, color: 'rgba(24, 144, 255, 0.1)'},
                    ])
                }
            },
            {
                name: '同比月',
                type: 'line',
                smooth: true,
                symbol: 'circle',
                symbolSize: 6,
                lineStyle: {
                    width: 2,
                    color: '#FAAD14',
                },
                itemStyle: {
                    color: '#FAAD14',
                    borderWidth: 2,
                    borderColor: '#fff',
                },
                encode: {
                    x: 'date',
                    y: data.yoy_month_key,
                },
                areaStyle: {
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                        {offset: 0, color: 'rgba(250, 173, 20, 0.5)'},
                        {offset: 1, color: 'rgba(250, 173, 20, 0.05)'},
                    ])
                }
            },
            {
                name: '环比月',
                type: 'line',
                smooth: true,
                symbol: 'circle',
                symbolSize: 6,
                lineStyle: {
                    width: 2,
                    color: '#52C41A',
                },
                itemStyle: {
                    color: '#52C41A',
                    borderWidth: 2,
                    borderColor: '#fff',
                },
                encode: {
                    x: 'date',
                    y: data.prev_month_key,
                },
                areaStyle: {
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                        {offset: 0, color: 'rgba(82, 196, 26, 0.5)'},
                        {offset: 1, color: 'rgba(82, 196, 26, 0.05)'},
                    ])
                }
            },
        ]
    };

    myChart.setOption(option);

    function formatValue(value) {
        if (value >= 1000) {
            return (value / 1000).toFixed(1) + 'k';
        }
        return value;
    }

    function formatRate(rate) {
        if (rate === null) return 'N/A';
        var symbol = rate >= 0 ? '+' : '';
        return symbol + rate.toFixed(1) + '%';
    }

});
