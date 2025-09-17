<?php
require_once "config.php";
require_once "CatApi.php";

$api = new CatApi(CAT_BREEDS_URL, CAT_API_KEY);

// fetchBreeds() to get all cat breeds from the API.
try {
    $api->fetchBreeds();
} catch (Exception $o) {
    $errorMessage = $o->getMessage();
}

//If user clicked "Random Meow" -> pick a random breed.
if (!empty($api->breeds)) {
    if (isset($_GET['random'])) {
        $selectedIndex = array_rand($api->breeds);
    } else {
        //If nothing selected -> default to the first breed (0)
        $selectedIndex = isset($_GET['breed']) ? (int)$_GET['breed'] : 0;
    }
    $breed = $api->getBreed($selectedIndex);
} else {
    $breed = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cat Breeds</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Cat Breeds</h1>
    <p>Select a breed or get a random cat!</p>
</header>

<main>
    <?php if (isset($errorMessage)): ?>
     <!-- show the error message If there was an error with the API -->
    <p><?= htmlspecialchars($errorMessage) ?></p>
    <?php else: ?>
    <form method="get">
        <label for="breed_selector">Select Breed:</label>
        <!--  Put onchange for make the random name of the form of names  -->
        <select name="breed" id="breed_selector" onchange="this.form.submit()">
            <option class="chooseMeow" value=""> Choose Meow </option>
            <?php foreach ($api->breeds as $index => $b): ?>
                <option value="<?= $index ?>" <?= (isset($_GET['breed']) && $_GET['breed'] == $index) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($b['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="random" value="1">Random Meow</button>
    </form>
    <!-- if user choose a breed, It will show the cat image-->
        <?php if ($breed): ?>
            <div class="api-card">
                <img src="<?= htmlspecialchars($breed['image']['url']) ?>"
                     alt="<?= htmlspecialchars($breed['name']) ?>" id="breed_image">
                <p id="breed_json"><strong>Temperament:</strong> <?= htmlspecialchars($breed['temperament']) ?></p>
            </div>
        <?php else: ?>
            <p>No breed data available.</p>
        <?php endif; ?>
    <?php endif; ?>
</main>
</body>
</html>

