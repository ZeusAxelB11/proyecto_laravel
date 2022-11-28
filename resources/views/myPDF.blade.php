<!DOCTYPE html>
<html>
<head>
    <title>PDF test</title>
    <style>
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
    <body>
        <h1>Pagina 1</h1>
        <div class="page-break"></div>
        <h1>{{ $title }}</h1>
        <p>{{ $date }}</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        <script type="text/php">
         if ( isset($pdf) ) {
         $pdf->page_script('
         $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
         $pdf->text(270, 730, "Pagina $PAGE_NUM de $PAGE_COUNT", $font, 10);
         ');
         }

        </script>
    </body>
</html>
