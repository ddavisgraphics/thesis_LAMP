<!DOCTYPE html>
<html lang="en">
<head>

    <title>{local var="pageName"} | TimeTracker5000 </title>

    <!-- Meta Information -->
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="HandheldFriendly" content="True">

    <!-- Author, Description, Favicon, and Keywords -->
    <meta name="author" content="WVU Libraries | {local var="meta_authors"}">
    <meta name="description" content="{local var="meta_description"}">
    <meta name="keywords" content="{local var="meta_keywords"}">
    <link rel="shortcut icon" href="https://wvrhc.lib.wvu.edu/favicon.ico">

    <!-- Project Specific Head Includes -->
    <?php recurseInsert("includes/headerIncludes.php","php") ?>
</head>

<body>

<header>
    <div class="container">
        <div class="siteTitle">
            <h1> {local var="appName"} </h1>
            <blockquote>
                Tracking your time, <br> enabling your productivity.
            </blockquote>
        </div>
        <nav class="actions">
            <?php recurseInsert("includes/nav.php","php") ?>
        </nav>
    </div>
</header>
