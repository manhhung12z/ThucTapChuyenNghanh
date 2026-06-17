document.addEventListener("DOMContentLoaded", function () {
    // 1. Khởi tạo biểu đồ doanh thu theo thời gian
    const ctxTimeline = document.getElementById('revenueTimelineChart');
    const revenueRangeSelect = document.getElementById('revenueTimeframe');
    const ctxPie = document.getElementById('categoryPieChart');
    let timelineChart = null;

    if (ctxTimeline) {
        timelineChart = new Chart(ctxTimeline, {
            type: 'line', 
            data: {
                labels  : [], // Nhãn sẽ được cập nhật từ dữ liệu AJAX
                datasets: [{
                    label: 'Doanh thu',
                    data: [], // Dữ liệu sẽ được cập nhật từ dữ liệu AJAX
                    borderColor: '#9333ea',
                    backgroundColor: 'rgba(147, 51, 234, 0.1)',
                    tension: 0.4, //do cong
                    fill: true 
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // tu dong keo dan khi thay doi kich thuoc
                scales: {
                y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return (value / 1000000) + 'M';
                                if (value >= 1000) return (value / 1000) + 'k';
                                return value;
                            }
                        }
                    }
                }
            }
        });
        function fetchChartData(range){
         const url = `${WEB_ROOT}/admin/DashboardController/getRevenueDataAPI?range=${encodeURIComponent(range)}`;
            fetch(url)
                .then(response => {
                    if(!response.ok) {
                        throw new Error('Mạng gặp sự cố không thể lấy dữ liệu biểu đồ');
                    }
                    return response.json();
                })
                .then(resData => {
                    // Cập nhật dữ liệu vào biểu đồ timeline
                    if (timelineChart && resData && resData.labels && resData.data) {
                        timelineChart.data.labels = resData.labels;// Cập nhật nhãn mới vào biểu đồ
                        timelineChart.data.datasets[0].data = resData.data;// Cập nhật dữ liệu mới vào biểu đồ
                        timelineChart.update();// Cập nhật biểu đồ với dữ liệu mới
                    } else {
                        console.error("Dữ liệu phản hồi API không đúng cấu trúc {labels:[], data:[]}");
                    }
            })
                .catch(error => {
                    console.error("Lỗi khi lấy dữ liệu biểu đồ:", error);
                });          
    }
    fetchChartData(revenueRangeSelect.value);// chay lan dau khi load trang
    revenueRangeSelect.addEventListener('change', function() { // chay khi thay doi select
        fetchChartData(this.value);
    });
    
    }


    
});