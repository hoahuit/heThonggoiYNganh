<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['MaAdmin'])) {
    header("Location: login.php");
    exit();
}

// Lấy thống kê cơ bản
$stats = [];

$stmt = $conn->query("SELECT COUNT(*) as total FROM HocSinh");
$stats['total_students'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $conn->query("SELECT COUNT(*) as total FROM GiaoVien");
$stats['total_teachers'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $conn->query("SELECT COUNT(*) as total FROM NganhHoc");
$stats['total_majors'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $conn->query("SELECT COUNT(*) as total FROM BaiTest");
$stats['total_tests'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $conn->query("SELECT COUNT(*) as total FROM CauHoi WHERE TrangThai = N'Chưa trả lời'");
$stats['pending_questions'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Thống kê bài test theo tháng
$stmt = $conn->query("
    SELECT MONTH(NgayLamBai) as month, COUNT(*) as count 
    FROM BaiTest 
    WHERE YEAR(NgayLamBai) = YEAR(GETDATE())
    GROUP BY MONTH(NgayLamBai)
    ORDER BY month
");
$monthly_tests = array_fill(1, 12, 0);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $monthly_tests[$row['month']] = $row['count'];
}

// Thống kê câu hỏi theo trạng thái
$stmt = $conn->query("
    SELECT TrangThai, COUNT(*) as count 
    FROM CauHoi 
    GROUP BY TrangThai
");
$question_stats = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $question_stats[$row['TrangThai']] = $row['count'];
}

// Thống kê ngành học được đề xuất nhiều nhất
$stmt = $conn->query("
    SELECT TOP 5 nh.TenNganh, COUNT(*) as count
    FROM BaiTest bt
    JOIN NganhHoc nh ON bt.MaNganh = nh.MaNganh
    GROUP BY nh.TenNganh
    ORDER BY count DESC
");
$major_stats = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $major_stats[$row['TenNganh']] = $row['count'];
}

ob_start();
?>

<style>
    .dashboard {
        padding: 20px;
        background: #f8f9fa;
        max-width: 1400px;
        margin: 0 auto;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: white;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        font-size: 24px;
        margin-bottom: 10px;
        color: #4e73df;
    }

    .stat-value {
        font-size: 24px;
        font-weight: bold;
        color: #5a5c69;
        margin: 10px 0;
    }

    .stat-label {
        color: #858796;
        font-size: 0.875rem;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-top: 20px;
    }

    .chart-card {
        background: white;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        height: 300px;
        display: flex;
        flex-direction: column;
    }

    .chart-title {
        font-size: 1rem;
        color: #4e73df;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e3e6f0;
    }

    @media (max-width: 1024px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    .chart-card.full-width {
        grid-column: 1 / -1;
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="dashboard">
    <h2 class="mb-4">Thống kê tổng quan</h2>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
            <div class="stat-value"><?php echo $stats['total_students']; ?></div>
            <div class="stat-label">Học sinh</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
            <div class="stat-value"><?php echo $stats['total_teachers']; ?></div>
            <div class="stat-label">Giáo viên</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-graduation-cap"></i></div>
            <div class="stat-value"><?php echo $stats['total_majors']; ?></div>
            <div class="stat-label">Ngành học</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-clipboard-question"></i></div>
            <div class="stat-value"><?php echo $stats['pending_questions']; ?></div>
            <div class="stat-label">Câu hỏi chờ trả lời</div>
        </div>
    </div>

    <div class="charts-grid">

        <div class="chart-card">
            <h3 class="chart-title">Trạng thái câu hỏi</h3>
            <canvas id="questionStatusChart"></canvas>
        </div>
        <div class="chart-card">
            <h3 class="chart-title">Top ngành học được đề xuất</h3>
            <canvas id="majorStatsChart"></canvas>
        </div>
        <br>
        <div class="chart-card full-width">
            <h3 class="chart-title">Thống kê bài test theo tháng</h3>
            <canvas id="monthlyTestsChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        padding: 10,
                        font: {
                            size: 11
                        }
                    }
                }
            }
        };

        new Chart(document.getElementById('monthlyTestsChart'), {
            type: 'line',
            data: {
                labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
                datasets: [{
                    label: 'Số lượng bài test',
                    data: <?php echo json_encode(array_values($monthly_tests)); ?>,
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                ...commonOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 10
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('questionStatusChart'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(<?php echo json_encode($question_stats); ?>),
                datasets: [{
                    data: Object.values(<?php echo json_encode($question_stats); ?>),
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                    borderWidth: 1
                }]
            },
            options: {
                ...commonOptions,
                cutout: '70%'
            }
        });

        new Chart(document.getElementById('majorStatsChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(<?php echo json_encode($major_stats); ?>),
                datasets: [{
                    label: 'Số lượt đề xuất',
                    data: Object.values(<?php echo json_encode($major_stats); ?>),
                    backgroundColor: '#4e73df',
                    borderRadius: 4
                }]
            },
            options: {
                ...commonOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 10
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
    });
</script>

<?php
$content = ob_get_clean();
include '../includes/layoutadmin.php';
?>