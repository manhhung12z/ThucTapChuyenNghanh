<?php
require_once(_DIR_ROOT . '/app/Controllers/Admin/AdminController.php');
require_once(_DIR_ROOT . '/app/Models/Admin/dashboard.php');
class DashboardController extends AdminController
{

        public function index()
    {   
        $dashboardModel = new dashboard();
        $TopRevenueProducts = $dashboardModel->getTopRevenueProducts(6);
        $thisMonthRevenue = $dashboardModel->getThisMonthRevenue();
        $last7DaysRevenue = $dashboardModel->getLast7DaysRevenue();
        $monthlyRevenueThisYear = $dashboardModel->getMonthlyRevenueThisYear();
        return $this->views("dashboard/index", [
            'TopRevenueProducts' => $TopRevenueProducts,
            'thisMonthRevenue' => $thisMonthRevenue,
            'last7DaysRevenue' => $last7DaysRevenue,
            'monthlyRevenueThisYear' => $monthlyRevenueThisYear
        ]);
    }
        public function getRevenueDataAPI()
    {
        header('Content-Type: application/json');
        $range = $_GET['range'] ?? 'month';
        $dashboardModel = new dashboard();
        $chartData = [
            'labels' => [],
            'data' => []
        ];
        if($range=='year'){
            $monthlyRevenueThisYear = $dashboardModel->getMonthlyRevenueThisYear();
            for($i = 1; $i <= 12; $i++) {
                $chartData['labels'][] = "Tháng " . $i;
                $chartData['data'][] = (float)($monthlyRevenueThisYear[$i] ?? 0);
            }
        } elseif($range=='7days'){
            $sevenDaysRevenue = $dashboardModel->getLast7DaysRevenue();
            foreach ($sevenDaysRevenue as $dateKey => $money) {
                $chartData['labels'][] = date('d/m', strtotime($dateKey));
                $chartData['data'][] = $money;
            }
        } else if ($range == 'month') {
            $thisMonthRevenue = $dashboardModel->getThisMonthRevenue();
            foreach($thisMonthRevenue as $dateKey => $money) {
                $chartData['labels'][] = "Ngày " . $dateKey;
                $chartData['data'][] = $money;
            } 
        } else {
            echo json_encode(['error' => 'Khoảng thời gian không hợp lệ']);
            exit;
            }
        
        echo json_encode($chartData);
        exit;
    }

}