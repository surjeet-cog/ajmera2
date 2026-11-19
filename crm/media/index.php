<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../auth.php';
require_once '../../dbc.php';

include '../includes/header.php';

$query = "
    SELECT
        id,
        title,
        external_link,
        read_time,
        description,
        image,
        media_date,
        status,
        created_at
    FROM media
    ORDER BY id DESC
";

$result = $conn->query($query);

if (!$result) {
    die("Database Query Error: " . $conn->error);
}

?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <h1 style="font-size:24px;font-weight:normal;margin:0;">
        News

        <a
            href="add.php"
            class="btn-wp ms-2 text-decoration-none"
        >
            <i class="fas fa-plus"></i>
            Add New
        </a>
    </h1>

</div>


<?php if (isset($_GET['msg'])): ?>

    <?php if ($_GET['msg'] === 'added'): ?>

        <div class="alert alert-success">
            News added successfully!
        </div>

    <?php elseif ($_GET['msg'] === 'updated'): ?>

        <div class="alert alert-success">
            News updated successfully!
        </div>

    <?php elseif ($_GET['msg'] === 'deleted'): ?>

        <div class="alert alert-success">
            News deleted successfully!
        </div>

    <?php endif; ?>

<?php endif; ?>


<div class="wp-card">

    <div class="table-responsive">

        <table class="table table-striped table-hover align-middle">

            <thead class="table-dark">

                <tr>
                    <th width="5%">ID</th>
                    <th width="10%">Image</th>
                    <th width="30%">Title</th>
                    <th width="12%">Read Time</th>
                    <th width="12%">Media Date</th>
                    <th width="8%">Status</th>
                    <th width="23%">Actions</th>
                </tr>

            </thead>

            <tbody>

            <?php if ($result->num_rows > 0): ?>

                <?php while ($row = $result->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?php echo (int)$row['id']; ?>
                        </td>


                        <td>

                            <?php if (!empty($row['image'])): ?>

                                <img
                                    src="../../<?php echo htmlspecialchars(
                                        $row['image'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>"
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


                        <td>

                            <?php
                            echo htmlspecialchars(
                                $row['read_time'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>

                            <?php if (!empty($row['read_time'])): ?>
                                min
                            <?php endif; ?>

                        </td>


                        <td>

                            <?php
                            if (!empty($row['media_date'])) {

                                echo date(
                                    'd M Y',
                                    strtotime($row['media_date'])
                                );

                            } else {

                                echo '-';

                            }
                            ?>

                        </td>


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


                        <td>

                            <?php if (!empty($row['external_link'])): ?>

                                <a
                                    href="<?php echo htmlspecialchars(
                                        $row['external_link'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    <i class="fas fa-external-link-alt"></i>
                                </a>

                            <?php endif; ?>


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
                                onclick="return confirm('Are you sure you want to delete this news?');"
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

                        No news found.

                        <br>

                        <a
                            href="add.php"
                            class="btn-wp text-decoration-none mt-2 d-inline-block"
                        >
                            <i class="fas fa-plus"></i>
                            Add News
                        </a>

                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>


<?php include '../includes/footer.php'; ?>