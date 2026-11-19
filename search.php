
<?php
require_once __DIR__ . '/dbc.php';
include 'header_css.php';

/*
|--------------------------------------------------------------------------
| Search Configuration
|--------------------------------------------------------------------------
| Image column names should match your database.
*/

$searchConfig = [

    'blogs' => [
        'title'       => 'title',
        'description' => 'content',
        'slug'        => 'slug',
        'image'       => 'image',
        'url'         => '/ajmera/blogs/',
        'type'        => 'Blog',
    ],

    'projects' => [
        'title'       => 'project_name',
        'description' => null,
        'slug'        => 'slug',
        'image'       => 'project_image',
        'url'         => '/ajmera/projects/',
        'type'        => 'Project',
        'conditions'  => [
            'status' => 1,
        ],
    ],

];


/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
*/

function searchEscape($value)
{
    return htmlspecialchars(
        (string)($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

function validIdentifier($name)
{
    return is_string($name)
        && preg_match('/^[a-zA-Z0-9_]+$/', $name);
}

function quoteIdentifier($name)
{
    if (!validIdentifier($name)) {
        throw new RuntimeException('Invalid database identifier.');
    }

    return '`' . $name . '`';
}


/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

$q = trim($_GET['q'] ?? '');
$results = [];
$error = '';
$maxPerTable = 50;

if ($q !== '') {

    $searchTerm = '%' . $q . '%';

    foreach ($searchConfig as $table => $config) {

        try {

            if (!validIdentifier($table)) {
                continue;
            }

            /*
             * Get available table columns
             */

            $columnResult = $conn->query(
                'SHOW COLUMNS FROM ' . quoteIdentifier($table)
            );

            if (!$columnResult) {
                continue;
            }

            $availableColumns = [];

            while ($column = $columnResult->fetch_assoc()) {
                $availableColumns[] = $column['Field'];
            }

            $columnResult->free();


            /*
             * Search columns
             */

            $searchColumns = $config['search_columns']
                ?? array_filter([
                    $config['title'] ?? null,
                    $config['description'] ?? null,
                ]);

            $searchColumns = array_values(array_unique(
                array_filter(
                    $searchColumns,
                    function ($column) use ($availableColumns) {
                        return in_array($column, $availableColumns, true);
                    }
                )
            ));

            if (!$searchColumns) {
                continue;
            }


            /*
             * Validate title column
             */

            $titleColumn = $config['title'] ?? null;

            if (
                !$titleColumn ||
                !in_array($titleColumn, $availableColumns, true)
            ) {
                continue;
            }


            /*
             * Validate slug column
             */

            $slugColumn = $config['slug'] ?? null;

            if (
                !$slugColumn ||
                !in_array($slugColumn, $availableColumns, true)
            ) {
                $slugColumn = null;
            }


            /*
             * Validate image column
             */

            $imageColumn = $config['image'] ?? null;

            if (
                !$imageColumn ||
                !in_array($imageColumn, $availableColumns, true)
            ) {
                $imageColumn = null;
            }


            /*
             * Build SELECT expressions
             */

            $titleExpression = quoteIdentifier($titleColumn);

            $slugExpression = $slugColumn
                ? quoteIdentifier($slugColumn)
                : "''";

            $imageExpression = $imageColumn
                ? quoteIdentifier($imageColumn)
                : "''";


            /*
             * Description columns
             */

            $descriptionColumns = $config['description_columns']
                ?? array_filter([$config['description'] ?? null]);

            $descriptionColumns = array_values(array_filter(
                array_unique($descriptionColumns),
                function ($column) use ($availableColumns) {
                    return in_array($column, $availableColumns, true);
                }
            ));

            if ($descriptionColumns) {

                $descriptionParts = [];

                foreach ($descriptionColumns as $column) {

                    $descriptionParts[] = 'CAST('
                        . quoteIdentifier($column)
                        . ' AS CHAR)';
                }

                $descriptionExpression = 'CONCAT_WS(\' - \', '
                    . implode(', ', $descriptionParts)
                    . ')';

            } else {

                $descriptionExpression = "''";
            }


            /*
             * Build SQL
             */

            $sql = 'SELECT '
                . $titleExpression . ' AS result_title, '
                . $descriptionExpression . ' AS result_description, '
                . $slugExpression . ' AS result_slug, '
                . $imageExpression . ' AS result_img '
                . 'FROM ' . quoteIdentifier($table)
                . ' WHERE (';


            /*
             * Search conditions
             */

            $searchConditions = [];

            foreach ($searchColumns as $column) {

                $searchConditions[] = 'CAST('
                    . quoteIdentifier($column)
                    . ' AS CHAR) LIKE ?';
            }

            $sql .= implode(' OR ', $searchConditions) . ')';


            /*
             * Apply configured conditions
             */

            $conditionValues = [];

            foreach (($config['conditions'] ?? []) as $column => $value) {

                if (
                    validIdentifier($column) &&
                    in_array($column, $availableColumns, true)
                ) {

                    $sql .= ' AND ' . quoteIdentifier($column) . ' = ?';

                    $conditionValues[] = $value;
                }
            }


            $sql .= ' ORDER BY ' . $titleExpression . ' ASC LIMIT '
                . (int)$maxPerTable;


            /*
             * Prepare and execute
             */

            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                continue;
            }

            $params = array_fill(
                0,
                count($searchConditions),
                $searchTerm
            );

            $params = array_merge($params, $conditionValues);

            $types = str_repeat('s', count($searchConditions));

            foreach ($conditionValues as $value) {
                $types .= is_int($value) ? 'i' : 's';
            }

            $stmt->bind_param($types, ...$params);


            if ($stmt->execute()) {

                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {

                    $slug = trim(
                        (string)($row['result_slug'] ?? '')
                    );

                    $results[] = [

                        'title' => $row['result_title'] ?? '',

                        'description' => $row['result_description'] ?? '',

                        'slug' => $slug,

                        'img' => trim(
                            (string)($row['result_img'] ?? '')
                        ),

                        'type' => $config['type'] ?? ucfirst($table),

                        'url' => $config['url'] ?? '',

                    ];
                }
            }

            $stmt->close();

        } catch (Throwable $e) {

            error_log('Search Error: ' . $e->getMessage());

            continue;
        }
    }


    /*
     * Sort results
     */

    usort($results, function ($a, $b) {

        return strcasecmp($a['title'], $b['title']);

    });
}


include __DIR__ . '/header.php';

?>

<style>

    header {
        background: #000;
        padding-bottom: 20px !important;
    }

    .search-result {
        padding: 22px 0;
        border-bottom: 1px solid #ddd;
        height: 100%;
    }

    .search-result h2 {
        font-size: 24px;
        margin: 15px 0 10px;
    }

    .search-result p {
        line-height: 1.7;
        margin: 10px 0;
    }

    .search-result a {
        color: #000;
        text-decoration: underline;
    }

    .search-result-type {
        display: inline-block;
        margin-bottom: 8px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #666;
    }

    .search-result-img {
        width: 100%;
        height: 240px;
        object-fit: cover;
        display: block;
    }

    .search-empty {
        padding: 25px 0;
    }

</style>


<div class="search-page top80 pad80">

    <div class="container">

        <h1>Search Results</h1>


        <?php if ($q !== ''): ?>


            <?php if (count($results) > 0): ?>


                <div class="row">

                    <?php foreach ($results as $item): ?>

                        <div class="col-lg-4 col-md-6 m-0">

                            <div class="search-result">

                                <span class="search-result-type">

                                    <?= searchEscape($item['type']) ?>

                                </span>


                                <?php if ($item['img'] !== ''): ?>

                                    <img
                                        src="<?= searchEscape($item['img']) ?>"
                                        class="search-result-img"
                                        alt="<?= searchEscape($item['title']) ?>"
                                        loading="lazy"
                                    >

                                <?php endif; ?>


                                <h2>

                                    <?= searchEscape($item['title']) ?>

                                </h2>


                                <?php

                                $description = trim(

                                    preg_replace(
                                        '/\s+/',
                                        ' ',
                                        strip_tags((string)$item['description'])
                                    )

                                );

                                if (function_exists('mb_substr')) {

                                    $description = mb_substr(
                                        $description,
                                        0,
                                        220
                                    );

                                } else {

                                    $description = substr(
                                        $description,
                                        0,
                                        220
                                    );

                                }

                                ?>


                                <?php if ($description !== ''): ?>

                                    <p>
                                        <?= searchEscape($description) ?>
                                    </p>

                                <?php endif; ?>


                                <?php if (
                                    $item['slug'] !== '' &&
                                    $item['url'] !== ''
                                ): ?>

                                    <a href="<?= searchEscape(
                                        rtrim($item['url'], '/') . '/'
                                        . rawurlencode($item['slug']) . '.php'
                                    ) ?>">

                                        View <?= searchEscape($item['type']) ?>

                                    </a>

                                <?php endif; ?>


                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>


            <?php else: ?>


                <div class="search-empty">

                    <p>
                        No results found for
                        <strong><?= searchEscape($q) ?></strong>.
                    </p>

                    <p>Try another keyword.</p>

                </div>


            <?php endif; ?>


        <?php else: ?>


            <p>Please enter a search term.</p>


        <?php endif; ?>


    </div>

</div>


<?php include __DIR__ . '/footer.php'; ?>
