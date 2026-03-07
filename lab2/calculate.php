<?php
if (isset($_POST['course'], $_POST['credits'], $_POST['grade'])) {
    $courses = $_POST['course'];
    $credits = $_POST['credits'];
    $grades = $_POST['grade'];
    $totalPoints = 0;
    $totalCredits = 0;

    echo "<link rel='stylesheet' href='style.css'>"; // استخدام التنسيق نفسه
    echo "<h1>GPA Result</h1>";
    echo "<table>";
    echo "<tr><th>Course</th><th>Credits</th><th>Grade Points</th><th>Total Points</th></tr>";

    for ($i = 0; $i < count($courses); $i++) {
        $courseName = htmlspecialchars($courses[$i]);
        $cr = floatval($credits[$i]);
        $g = floatval($grades[$i]);

        if ($cr <= 0) continue; // تخطي القيم غير الصالحة [cite: 279]

        $pts = $cr * $g; // حساب نقاط المادة [cite: 281, 282]
        $totalPoints += $pts;
        $totalCredits += $cr;

        echo "<tr>
                <td>$courseName</td>
                <td>$cr</td>
                <td>$g</td>
                <td>$pts</td>
              </tr>";
    }
    echo "</table>";

    if ($totalCredits > 0) {
        $gpa = $totalPoints / $totalCredits; // حساب المعدل النهائي [cite: 309]
        
        // تحديد التفسير بناءً على المعدل [cite: 310, 315, 319, 324]
        if ($gpa >= 3.7) $interpretation = "Distinction";
        elseif ($gpa >= 3.0) $interpretation = "Merit";
        elseif ($gpa >= 2.0) $interpretation = "Pass";
        else $interpretation = "Fail";

        echo "<p>Your GPA is <strong>" . number_format($gpa, 2) . "</strong> ($interpretation).</p>";
    } else {
        echo "<p>No valid courses entered.</p>";
    }
} else {
    echo "Data not received.";
}
?>
