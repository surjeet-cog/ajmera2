<?php

require_once '../auth.php';
require_once '../../dbc.php';

include '../includes/header.php';


// Fetch all blogs
$query = "
    SELECT
        id,
        title,
        category,
        status,
        publish_date,
        image
    FROM blogs
    ORDER BY id DESC
";

$result = $conn->query($query);

if (!$result) {
    die("Database Query Error: " . $conn->error);
}

?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <h1 style="font-size:24px; font-weight:normal; margin:0;">
        Blogs

        <a href="add.php"
           class="btn-wp ms-2 text-decoration-none">
            <i class="fas fa-plus"></i>
            Add New
        </a>
    </h1>

</div>


<div class="wp-card">

    <div class="table-responsive">

        <table class="table table-striped table-hover align-middle">

            <thead class="table-dark">

                <tr>
                    <th width="5%">ID</th>
                    <th width="10%">Image</th>
                    <th width="30%">Title</th>
                    <th width="15%">Category</th>
                    <th width="10%">Status</th>
                    <th width="15%">Publish Date</th>
                    <th width="15%">Actions</th>
                </tr>

            </thead>

            <tbody>

            <?php if ($result->num_rows > 0): ?>

                <?php while ($row = $result->fetch_assoc()): ?>

                    <tr>

                        <!-- ID -->
                        <td>
                            <?php echo (int)$row['id']; ?>
                        </td>


                        <!-- Image -->
                        <td>

                            <?php if (!empty($row['image'])): ?>

                                <img
                                    src="../../<?php echo htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8'); ?>"
                                    alt="Blog Image"
                                    width="70"
                                    height="45"
                                    style="
                                        object-fit:cover;
                                        border-radius:4px;
                                        border:1px solid #ddd;
                                    "
                                    onerror="this.style.display='none';"
                                >

                            <?php else: ?>

                                <span class="text-muted">
                                    No Image
                                </span>

                            <?php endif; ?>

                        </td>


                        <!-- Title -->
                        <td>

                            <strong>
                                <?php
                                echo htmlspecialchars(
                                    $row['title'] ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </strong>

                        </td>


                        <!-- Category -->
                        <td>

                            <?php
                            echo htmlspecialchars(
                                $row['category'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>

                        </td>


                        <!-- Status -->
                        <td>

                            <?php if ((int)$row['status'] === 1): ?>

                                <span class="badge bg-success">
                                    Active
                                </span>

                            <?php else: ?>

                                <span class="badge bg-secondary">
                                    Inactive
                                </span>

                            <?php endif; ?>

                        </td>


                        <!-- Publish Date -->
                        <td>

                            <?php

                            if (!empty($row['publish_date'])) {

                                echo date(
                                    'd M Y',
                                    strtotime($row['publish_date'])
                                );

                            } else {

                                echo '-';

                            }

                            ?>

                        </td>


                        <!-- Actions -->
                        <td>

                            <a
                                href="edit.php?id=<?php echo (int)$row['id']; ?>"
                                class="btn-wp text-decoration-none"
                            >
                                <i class="fas fa-edit"></i>
                                Edit
                            </a>


                            <a
                                href="delete.php?id=<?php echo (int)$row['id']; ?>"
                                class="btn-wp-danger text-decoration-none"
                                onclick="return confirm('Are you sure you want to delete this blog?');"
                            >
                                <i class="fas fa-trash"></i>
                                Trash
                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>


            <?php else: ?>

                <tr>

                    <td
                        colspan="7"
                        class="text-center py-4"
                    >

                        No blogs found.

                        <br>

                        <a
                            href="add.php"
                            class="btn-wp text-decoration-none mt-2 d-inline-block"
                        >
                            <i class="fas fa-plus"></i>
                            Add New Blog
                        </a>

                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>


<?php include '../includes/footer.php'; ?>