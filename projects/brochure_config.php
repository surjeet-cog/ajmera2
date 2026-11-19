
<?php
$brochureProjects = [
    'ajmera-annex.php' => [
        'slug' => 'ajmera-annex',
        'name' => 'Ajmera Annex',
        'pdf'  => 'pdf/.pdf'
    ],
    'ajmera-arham.php' => [
        'slug' => 'ajmera-arham',
        'name' => 'Ajmera Arham',
        'pdf'  => 'pdf/Ajmera_Arham_brochure.pdf'
    ],
    'ajmera-aria.php' => [
        'slug' => 'ajmera-aria',
        'name' => 'Ajmera Aria',
        'pdf'  => 'pdf/.pdf'
    ],
    'ajmera-eden.php' => [
        'slug' => 'ajmera-eden',
        'name' => 'Ajmera Eden',
        'pdf'  => 'pdf/.pdf'
    ],
    'ajmera-greenfinity.php' => [
        'slug' => 'ajmera-greenfinity',
        'name' => 'Ajmera Greenfinity',
        'pdf'  => 'pdf/.pdf'
    ],
    'ajmera-i-land-aeon.php' => [
        'slug' => 'ajmera-i-land-aeon',
        'name' => 'Ajmera i Land Aeon',
        'pdf'  => 'pdf/.pdf'
    ],
    'ajmera-i-land-treon.php' => [
        'slug' => 'ajmera-i-land-treon',
        'name' => 'Ajmera i Land Treon',
        'pdf'  => 'pdf/.pdf'
    ],
    'ajmera-i-land-zeon.php' => [
        'slug' => 'ajmera-i-land-zeon',
        'name' => 'Ajmera i Land Zeon',
        'pdf'  => 'pdf/.pdf'
    ],
    'ajmera-icrc.php' => [
        'slug' => 'ajmera-icrc',
        'name' => 'Ajmera ICRC',
        'pdf'  => 'pdf/.pdf'
    ],
    'ajmera-iris.php' => [
        'slug' => 'ajmera-iris',
        'name' => 'Ajmera Iris',
        'pdf'  => 'pdf/Ajmera-Iris-Brochure.pdf'
    ],
    'ajmera-manhattan.php' => [
        'slug' => 'ajmera-manhattan',
        'name' => 'Manhattan',
        'pdf'  => 'pdf/Manhattan-Brochure.pdf'
    ],
    'ajmera-marina.php' => [
        'slug' => 'ajmera-marina',
        'name' => 'Ajmera Marina',
        'pdf'  => 'pdf/Ajmera-Marina-Brochure.pdf'
    ],
    'ajmera-nucleus-commercial.php' => [
        'slug' => 'ajmera-nucleus-commercial',
        'name' => 'Ajmera Nucleus Commercial',
        'pdf'  => 'pdf/.pdf'
    ],
    'ajmera-nucleus-residential.php' => [
        'slug' => 'ajmera-nucleus-residential',
        'name' => 'Ajmera Nucleus',
        'pdf'  => 'pdf/.pdf'
    ],
    'ajmera-prive.php' => [
        'slug' => 'ajmera-prive',
        'name' => 'Ajmera Prive',
        'pdf'  => 'pdf/.pdf'
    ],
    'ajmera-shikhar.php' => [
        'slug' => 'ajmera-shikhar',
        'name' => 'Ajmera Shikhar',
        'pdf'  => 'pdf/.pdf'
    ],
    'ajmera-solis.php' => [
        'slug' => 'ajmera-solis',
        'name' => 'Ajmera Solis',
        'pdf'  => 'pdf/Ajmera-Solis-brochure.pdf'
    ],
    'ajmera-summit.php' => [
        'slug' => 'ajmera-summit',
        'name' => 'Ajmera Summit',
        'pdf'  => 'pdf/.pdf'
    ],
    'ajmera-vihara.php' => [
        'slug' => 'ajmera-vihara',
        'name' => 'Ajmera Vihara',
        'pdf'  => 'pdf/Ajmera-Vihara-Brochure.pdf.pdf'
    ],
    'casa-vyoma.php' => [
        'slug' => 'casa-vyoma',
        'name' => 'Casa Vyoma',
        'pdf'  => 'pdf/.pdf'
    ],
    'vann-by-ajmera.php' => [
        'slug' => 'vann-by-ajmera',
        'name' => 'Vann By Ajmera',
        'pdf'  => 'pdf/ajmera-Vann-Brochure.pdf'
    ],
    'ajmera-elevate.php' => [
        'slug' => 'ajmera-elevate',
        'name' => 'Ajmera Elevate',
        'pdf'  => 'pdf/'
    ],
    '33fifteen.php' => [
        'slug' => '33fifteen',
        'name' => '33fifteen',
        'pdf'  => 'pdf/'
    ],
    'ajmera-lakeside-paradise.php' => [
        'slug' => 'ajmera-lakeside-paradise',
        'name' => 'Ajmera Lakeside Paradise',
        'pdf'  => 'pdf/.pdf'
    ]
];

$currentPage = basename($_SERVER['PHP_SELF']);
$currentBrochure = $brochureProjects[$currentPage] ?? null;
