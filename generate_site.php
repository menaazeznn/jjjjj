<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['siteTitle'];
    $description = $_POST['siteDescription'];

    $websiteHTML = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>$title</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f9;
                }
                header {
                    background-color: #333;
                    color: #fff;
                    padding: 1rem;
                    text-align: center;
                }
                main {
                    padding: 2rem;
                }
            </style>
        </head>
        <body>
            <header>
                <h1>$title</h1>
            </header>
            <main>
                <p>$description</p>
            </main>
        </body>
        </html>
    ";

    echo "<h2>Your website is ready!</h2>";
    echo "<pre>$websiteHTML</pre>";
}
?>
