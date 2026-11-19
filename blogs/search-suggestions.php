<?php
require_once '../dbc.php';

if (isset($_POST['keyword'])) {

    $keyword = trim($_POST['keyword']);

    if (strlen($keyword) < 2) {
        exit;
    }

    $search = "%" . $keyword . "%";

    $stmt = $conn->prepare("
        SELECT title, slug
        FROM blogs
        WHERE status = 1
        AND (title LIKE ? OR content LIKE ?)
        ORDER BY id DESC
        LIMIT 8
    ");

    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {

            $title = htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');
            $slug = htmlspecialchars($row['slug'], ENT_QUOTES, 'UTF-8');

         echo '<a href="' . $slug . '"
    class="suggestion-item d-block text-decoration-none text-dark">'
    . $title .
'</a>';
        }

    } else {

        echo '<div class="suggestion-item text-muted">
                No results found
              </div>';

    }

    $stmt->close();
}
?>