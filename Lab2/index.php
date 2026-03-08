<?php
// إعداد متغيرات لتخزين النتائج وعرضها لاحقاً في الصفحة
$result = "";
$tableHtml = "";

// التحقق مما إذا تم إرسال النموذج باستخدام طريقة POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $courses = $_POST['course'] ?? []; // استلام أسماء المواد [cite: 1311, 1312]
    $credits = $_POST['credits'] ?? []; // استلام الساعات المعتمدة [cite: 1316, 1317]
    $grades = $_POST['grade'] ?? []; // استلام الدرجات [cite: 1320, 1321]
    
    $totalPoints = 0;
    $totalCredits = 0;

    // بناء جدول النتائج برمجياً
    $tableHtml = "<table>";
    $tableHtml .= "<tr><th>Course</th><th>Credits</th><th>Grade</th><th>Grade Points</th></tr>";

    for ($i = 0; $i < count($courses); $i++) {
        $course = htmlspecialchars($courses[$i]);
        $cr = floatval($credits[$i]);
        $g = floatval($grades[$i]);

        if ($cr <= 0) continue; // تجاهل المدخلات غير الصالحة [cite: 1352]

        $pts = $cr * $g; // حساب نقاط المادة [cite: 1355]
        $totalPoints += $pts;
        $totalCredits += $cr;

        $tableHtml .= "<tr><td>$course</td><td>$cr</td><td>$g</td><td>$pts</td></tr>";
    }
    $tableHtml .= "</table>";

    // حساب المعدل النهائي وتحديد التفسير [cite: 1378, 1379]
    if ($totalCredits > 0) {
        $gpa = $totalPoints / $totalCredits;
        
        // منطق تحديد التقدير بناءً على المعدل [cite: 1380, 1384, 1387]
        if ($gpa >= 3.7) {
            $interpretation = "Distinction";
        } elseif ($gpa >= 3.0) {
            $interpretation = "Merit";
        } elseif ($gpa >= 2.0) {
            $interpretation = "Pass";
        } else {
            $interpretation = "Fail";
        }

        $result = "Your GPA is " . number_format($gpa, 2) . " ($interpretation)."; [cite: 1392, 1395]
    } else {
        $result = "No valid courses entered.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GPA Calculator - Step 2</title>
    <link rel="stylesheet" href="style.css"> <script src="script.js"></script> </head>
<body>
    <h1>GPA Calculator</h1>

    <?php if ($result != ""): ?>
        <?php echo $tableHtml; ?>
        <p><strong><?= $result ?></strong></p>
    <?php endif; ?>

    <form action="" method="post" onsubmit="return validateForm();">
        <div id="courses">
            <div class="course-row">
                <label>Course: </label>
                <input type="text" name="course[]" placeholder="e.g. Mathematics" required>
                
                <label>Credits: </label>
                <input type="number" name="credits[]" placeholder="e.g. 3" min="1" required>
                
                <label>Grade: </label>
                <select name="grade[]">
                    <option value="4.0">A</option>
                    <option value="3.0">B</option>
                    <option value="2.0">C</option>
                    <option value="1.0">D</option>
                    <option value="0.0">F</option>
                </select>
            </div>
        </div>
        <br>
        <button type="button" onclick="addCourse()">+ Add Course</button>
        <br><br>
        <input type="submit" value="Calculate GPA">
    </form>
</body>
</html>
