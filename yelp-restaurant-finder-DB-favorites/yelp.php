<?php
session_start();

// Yelp API key
$MY_YELP_API_KEY = 'JjpnGZMMY5_J5AocEgN5ahmhSPfcDIOovV6aIJRq18UBUaJW-I2eIZHbBnO3LAulPU45Yzj-go0xIcrJ3Q-f04sLR0jeHwsXHic-t_C8KlTNwFw4HNASP_6oBtwaZ3Yx';

// Connecting to MySQL using PDO
try {
    $dbConnection = new PDO("mysql:host=127.0.0.1;dbname=yelp", "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $error) {
    die("Connection Error: " . $error->getMessage());
}

// Initialize session variables if not set
if (!isset($_SESSION["recent_city"])) $_SESSION["recent_city"] = '';
if (!isset($_SESSION["search_data"])) $_SESSION["search_data"] = [];

// reset functionality
if (isset($_GET['clear_all'])) {
    $_SESSION["recent_city"] = '';
    $_SESSION["search_data"] = [];
}

// Adding a restaurant to the favorites database table
if (isset($_GET['save_favorite'])) {
    $chosen_id = $_GET['save_favorite'];
    
    // Check if the restaurant exists in the current search data
    if (isset($_SESSION["search_data"][$chosen_id])) {
        $chosen_restaurant = $_SESSION["search_data"][$chosen_id];
        
        // Insert restaurant into the favorites table
        $insert_query = $dbConnection->prepare('INSERT INTO favorites (id, name, image_url, yelp_page_url, categories, price, rating, address, phone) 
                                                VALUES (:id, :name, :image_url, :yelp_page_url, :categories, :price, :rating, :address, :phone)
                                                ON DUPLICATE KEY UPDATE id=id');
        $insert_query->execute([
            ':id' => $chosen_restaurant['id'],
            ':name' => $chosen_restaurant['name'],
            ':image_url' => $chosen_restaurant['image_url'],
            ':yelp_page_url' => $chosen_restaurant['url'],
            ':categories' => implode(", ", array_column($chosen_restaurant['categories'], 'title')),
            ':price' => isset($chosen_restaurant['price']) ? $chosen_restaurant['price'] : null,
            ':rating' => $chosen_restaurant['rating'],
            ':address' => implode(", ", $chosen_restaurant['location']['display_address']),
            ':phone' => $chosen_restaurant['display_phone']
        ]);
    }
}

// Yelp API search
if (isset($_GET['location']) && isset($_GET['keywords'])) {
    $_SESSION["recent_city"] = $_GET['location'];
    $encoded_city = urlencode($_GET['location']);
    $encoded_keywords = urlencode($_GET['keywords']);

    $yelp_request_url = "https://api.yelp.com/v3/businesses/search?location={$encoded_city}&term={$encoded_keywords}&limit=10";
    $header_settings = [
        "Authorization: Bearer $MY_YELP_API_KEY",
    ];

    $curl_session = curl_init();
    curl_setopt_array($curl_session, [
        CURLOPT_URL => $yelp_request_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $header_settings,
    ]);
    $yelp_response = curl_exec($curl_session);
    curl_close($curl_session);

    $decoded_data = json_decode($yelp_response, true);

    // Store search results in the session
    if (isset($decoded_data['businesses'])) {
        foreach ($decoded_data['businesses'] as $restaurant) {
            $_SESSION["search_data"][$restaurant['id']] = $restaurant;
        }
    }
}

// Retrieve favorite restaurants from the database
$retrieve_favorites = $dbConnection->prepare('SELECT * FROM favorites');
$retrieve_favorites->execute();
$favorites_list = $retrieve_favorites->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>YELP Restaurant</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .wrapper { display: flex; gap: 20px; }
        .section { width: 48%; }
        img { width: 120px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h1>YELP Restaurant</h1>

    <!-- Search Form -->
    <form action="yelp.php" method="get">
        <label>City:
            <input type="text" name="location" value="<?= htmlspecialchars($_SESSION["recent_city"]) ?>" required>
        </label>
        <label>Search Terms:
            <input type="text" name="keywords" required>
        </label>
        <button type="submit">Search</button>
        <a href="yelp.php?clear_all" style="padding-left:10px;">
            <button type="button">Clear</button>
        </a>
    </form>

    <hr>

    <div class="wrapper">
        
        <div class="section">
            <h2>Search Term Results</h2>
            <?php if (!empty($_SESSION["search_data"])): ?>
                <table>
                    <tr>
                        <th>Picture</th>
                        <th>Name</th>
                        <th>Rating</th>
                        <th>Address</th>
                    </tr>
                    <?php foreach ($_SESSION["search_data"] as $restaurant): ?>
                        <tr>
                            <td>
                                <a href="yelp.php?save_favorite=<?= $restaurant['id'] ?>">
                                    <img src="<?= $restaurant['image_url'] ?>" alt="Restaurant Image">
                                </a>
                            </td>
                            <td><?= htmlspecialchars($restaurant['name']) ?></td>
                            <td><?= htmlspecialchars($restaurant['rating']) ?> / 5</td>
                            <td><?= implode(", ", $restaurant['location']['display_address']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No restaurants found the search terms.</p>
            <?php endif; ?>
        </div>

        
        <div class="section">
            <h2>Favorite Restaurants List</h2>
            <?php if (!empty($favorites_list)): ?>
                <table>
                    <tr>
                        <th>Picture</th>
                        <th>Name</th>
                        <th>Rating</th>
                        <th>Address</th>
                    </tr>
                    <?php foreach ($favorites_list as $favorite): ?>
                        <tr>
                            <td>
                                <img src="<?= $favorite['image_url'] ?>" alt="Favorite Image">
                            </td>
                            <td><?= htmlspecialchars($favorite['name']) ?></td>
                            <td><?= htmlspecialchars($favorite['rating']) ?> / 5</td>
                            <td><?= htmlspecialchars($favorite['address']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No favorite restaurants.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
