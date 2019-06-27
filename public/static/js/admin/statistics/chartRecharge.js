
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('cpu-load'));

        myChart.setOption({
            title: {
                text: '充值统计图'
            },
            tooltip : {
                trigger: 'axis',
                axisPointer: {
                    type: 'cross',
                    label: {
                        backgroundColor: '#6a7985'
                    }
                }
            },
            legend: {
                data: []
            },
            toolbox: {
                feature: {
                    saveAsImage: {}
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis : [
                {
                    type : 'category',
                    boundaryGap : false,
                    data: []
                }
            ],
            yAxis : [
                {
                    type : 'value'
                }
            ],
            series: []
        });

        

        $.get('/index.php/admin/statistics/chartRecharge').done(function (data){
            // console.log(data);
            // console.log(data.arr);
            // console.log(data.dates);
            myChart.setOption({
                legend: {
                    data: data.legend
                },
                xAxis: {
                    data: data.dates
                },
                series: data.arr
            });
        });

        var hh = $("#queryForm");
        $("#sub").click(function(){
            
            var hhs = hh.serializeArray();
            console.log(hhs);
            var obj = { };            
            
            for (var item in hhs){
                obj[hhs[item].name] = hhs[item].value;
            }
            var startTime = obj.startTime;
            var endTime = obj.endTime;
            // console.log(startTime);
            // console.log(obj);

            jQuery.get('/index.php/admin/statistics/chartRecharge',{startTime:startTime, endTime: endTime}).done(function (data){
                if(data.status == 2){
                    alert('时间范围选择错误');
                    return false;
                }
                myChart.setOption({
                    legend: {
                        data: data.legend
                    },
                    xAxis: {
                        data: data.dates
                    },
                    series: data.arr
                });
            });
            // jQuery.ajax({
            //     cache: false,
            //     type: "get",
            //     url:"/index.php/admin/statistics/chartRecharge",
            //     data:obj,
            //     // async: true,
            //     success: function(data) {
            //         alert('hhh');
            //     },
            // });

        });