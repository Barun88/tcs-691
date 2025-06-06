<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // redirect to login page if not log in
    header(header: 'Location: login.php');
    exit();
}

// User is logged in get their username
$username = $_SESSION['username'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF_8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cat-A-Log</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div id="top_navbar">
        <a href="#cat_a_log" class="top_navbar_opts">Cat-A-Log</a>
        <span class="top_navbar_opts">Welcome, <?php echo htmlspecialchars(string: $username); ?>!</span>
        <a href="logout.php" class="top_navbar_opts" id="logout_link">Logout</a>
    </div>

    <div id="spotlight">
        <h2>Spotlight</h2>
      
        <div class="album_container">
          <div class="album_card" id="album1">
            <img class="album_cover" src="https://upload.wikimedia.org/wikipedia/en/f/f6/Kendrick_Lamar_-_To_Pimp_a_Butterfly.png" alt="TPAB">
            <h3 class="album_name" id="aName1">To Pimp A Butterfly</h3>
            <p class="artist_name" id="artName1">Kendrick Lamar</p>
          </div>
      
          <div class="album_card">
            <img class="album_cover" src="https://upload.wikimedia.org/wikipedia/en/3/34/Sufjan_Stevens_The_Ascension_Cover.png" alt="TheAscension">
            <h3 class="album_name">The Ascension</h3>
            <p class="artist_name">Sufjan Stevens</p>
          </div>
      
          <div class="album_card">
            <img class="album_cover" src="https://upload.wikimedia.org/wikipedia/en/5/56/ModalMusic.jpg" alt="ModalSoul">
            <h3 class="album_name">Modal Soul</h3>
            <p class="artist_name">Nujabes</p>
          </div>
        </div>
      </div>
      

    <div id="by_genre"></div>

    <div id="gif_hover_card">
        <img src="src/static.jpg" alt="static_vinyl" id="static_gif">
        <img src="src/bg_gif.gif" alt="animated_vinyl" id="animated_gif">
    </div>

  <div id="music_player">
    <button id="previous_btn"><b><<</b></button>
    <button id="play_pause">&#9658;</button>
    <button id="next_btn"><b>>></b></button>
    <span id="track_name">No Track</span>
    <input type="range" id="seek_bar" value="0" min="0" max="100" step="0.1">
    <span id="current_time">0:00</span> / <span id="duration">0:00</span>
    <audio id="audio_player" src="src/cat-meow-321642.mp3"></audio>
  </div>
    
    <script src="playback.js"></script>
    <script src="behaviour.js"></script>
</body>
</html>