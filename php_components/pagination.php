<?php


require 'vendor/autoload.php';
use GuzzleHttp\Client;

// config.php
define('API_KEY', '71b1966355ddca4fec1345cac864a220');
define('RESULTS_PER_PAGE', 16);

// flickr.php
function fetch_photos($text, $page) {
    $client = new Client();
    $url = "https://www.flickr.com/services/rest/";
    $response = $client->request('GET', $url, [
        'query' => [
            'method' => 'flickr.photos.search',
            'api_key' => API_KEY,
            'text' => $text,
            'format' => 'json',
            'nojsoncallback' => 1,
            'extras' => 'url_s',
            'per_page' => RESULTS_PER_PAGE,
            'page' => $page
        ]
    ]);

    if ($response->getStatusCode() != 200) {
        throw new Exception('Error: ' . $response->getStatusCode());
    }

    return json_decode($response->getBody(), true);
}

// index.php
try {
    $text = isset($_GET['search']) ? urlencode($_GET['search']) : '';
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $data = fetch_photos($text, $page);
    echo '<div class="grid-container">';
    if ($data['stat'] == 'ok') {
        if ($data['photos']['total'] > 0) {
            foreach ($data['photos']['photo'] as $photo) {
                echo '<div class="card">';
                echo '<img src="' . $photo['url_s'] . '" alt="' . $photo['title'] . '">';
                echo '<div class="show-button">';
                echo '  <a href="' . $photo['url_s'] . '" target="_blank"> <button>Show Picture</button> </a>';
                echo ' </div>';
                echo '<div class="container">';
                echo '</div>';
                echo '<p class="photo-title">'. $photo['title'] .'</p>';
                echo '</div>';
            }
            echo '</div>';
            echo '<div class="pagination">';
            $number_of_pages = $data['photos']['pages'];
            if ($page > 1) {
                echo '<a href="index.php?page=1&search=' . $text . '" class="pagination-button">First Page</a>  ';
                echo '<a href="index.php?page=' . ($page - 1) . '&search=' . $text . '" class="pagination-button" >Previous Page</a>  ';
            }
            for ($i = max(1, $page - 2); $i <= min($number_of_pages, $page + 2); $i++) {
                if ($i == $page) {
                    echo '<a href="index.php?page=' . $i . '&search=' . $text . '" class="pagination-button active">' . $i . '</a>  ';
                } else {
                    echo '<a href="index.php?page=' . $i . '&search=' . $text . '" class="pagination-button">' . $i . '</a>  ';
                }
            }
            if ($page < $number_of_pages) {
                echo '<a href="index.php?page=' . ($page + 1) . '&search=' . $text . '" class="pagination-button">Next Page</a>  ';
                echo '<a href="index.php?page=' . $number_of_pages . '&search=' . $text . '" class="pagination-button">Last Page</a>';
            } 
            echo '</div><br><br>';
        } else {
            echo '<h1>No results found.</h1>';
        }
    } else {
        echo '<h1>Search any photo you want :) </h1>';
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>