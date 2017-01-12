<?php

Parse_Results: {
    require __DIR__ . '/libs/parse_results.php';
    $results = parse_results(__DIR__ . '/output/results.hello_world.log');
}

Load_Theme: {
    $theme = isset($_GET['theme']) ? $_GET['theme'] : 'default';
    if (! ctype_alnum($theme)) {
        exit('Invalid theme');
    }

    if ($theme === 'default') {
        require __DIR__ . '/libs/make_graph.php';
    } else {
        $file = __DIR__ . '/libs/' . $theme . '/make_graph.php';
        if (is_readable($file)) {
            require $file;
        } else {
            require __DIR__ . '/libs/make_graph.php';
        }
    }
}

// RPS Benchmark
list($chart_rpm, $div_rpm) = make_graph('rps', 'Throughput', 'requests per second');

// Memory Benchmark
list($chart_mem, $div_mem) = make_graph('memory', 'Memory', 'peak memory (MB)');

// Exec Time Benchmark
list($chart_time, $div_time) = make_graph('time', 'Exec Time', 'ms');

// Included Files
list($chart_file, $div_file) = make_graph('file', 'Included Files', 'count');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>PHP Framework Benchmark</title>
<style>
body {
  font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif;
  -webkit-font-smoothing:antialiased;
  -moz-osx-font-smoothing:grayscale;
  padding: 20px;
}
</style>
<script src="https://www.google.com/jsapi"></script>
<script>
  <?php echo $chart_rpm, $chart_mem, $chart_time, $chart_file; ?>
</script>
</head>
<body>
<h1>PHP Framework Benchmark</h1>

<p>Testing on: <a href="https://m.do.co/c/6a3562fcb310">Digital Ocean</a> Ubuntu 16.04.1 x64 2gb / 2CPU droplet</p>
<p>Testing with: siege -c 10 -t 3s -r 3 -b -q</p>
<p>PHP Info: <a href="phpinfo.php">Here</a></p>
<p>Server Install: <a href="install.sh">Here</a></p>
<p>Webgrind: <a href="http://dev-mvcbenchmark.asked.io/webgrind">Here</a>, append ?XDEBUG_PROFILE (on the <a href="http://dev-mvcbenchmark.asked.io/">dev site</a>) to the MVC test url to log data.</p>

<p>Requests
        <ul>
                <li>https://github.com/radarphp/Radar.Project</li>
                <li>tweet @asked_io to request</li>
        </ul>
</p>



<div>
<?php
echo $div_rpm, $div_mem, $div_time, $div_file;
?>
</div>

<ul>
<?php
$url_file = __DIR__ . '/output/urls.log';
if (file_exists($url_file)) {
    $urls = file($url_file);
    foreach ($urls as $url) {
        $parts = parse_url(trim($url));
        $url = $parts['scheme'] . '://' . $_SERVER['HTTP_HOST'] . $parts['path'];
        if ($parts['query']) {
            $url .= '?' . $parts['query'];
        }
        echo '<li><a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') .
             '">' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') .
             '</a></li>' . "\n";
    }
}
?>
</ul>

<hr>

<footer>
<p style="text-align: right">This page is a part of <a href="https://github.com/Askedio/php-framework-benchmark">php-framework-benchmark</a>.</p>
<p style="text-align: right">- originally from kenjis <a href="https://github.com/kenjis/php-framework-benchmark">php-framework-benchmark</a>.</p>
</footer>
</body>
</html>
