const audio = document.getElementById('audio_player');
const play_pause = document.getElementById('play_pause');
const seek_bar = document.getElementById('seek_bar');
const current_time = document.getElementById('current_time');
const duration = document.getElementById('duration');
const track_name = document.getElementById('track_name');

let isPlaying = false;
let isReady = false;

// Format seconds into mm:ss
function formatTime(seconds) {
  if (isNaN(seconds) || seconds < 0) return "0:00";
  const mins = Math.floor(seconds / 60);
  const secs = Math.floor(seconds % 60);
  return `${mins}:${secs < 10 ? '0' + secs : secs}`;
}

// Load and play a new track
function loadTrack(src, name) {
  console.log('Loading track:', src); // Debug log
  isReady = false;
  isSeeking = false;
  audio.src = src;
  track_name.textContent = name;
  audio.load();

  // Wait for metadata to be ready
  audio.addEventListener('loadedmetadata', () => {
    console.log('Metadata loaded, duration:', audio.duration); // Debug log
    isReady = true;
    seek_bar.value = 0;
    current_time.textContent = "0:00";
    duration.textContent = formatTime(audio.duration);
  }, { once: true });

  // Play track
  audio.play()
    .then(() => {
      isPlaying = true;
      play_pause.innerHTML = "❚❚";
    })
    .catch(err => {
      console.error("Playback failed:", err);
    });
}

// Toggle play/pause
play_pause.addEventListener('click', () => {
  if (audio.paused) {
    audio.play();
    isPlaying = true;
    play_pause.innerHTML = "❚❚";
  } else {
    audio.pause();
    isPlaying = false;
    play_pause.innerHTML = "▶";
  }
});

audio.addEventListener('timeupdate', () => {
  if (isReady && !isSeeking) {
    const progress = (audio.currentTime / audio.duration) * 100;
    seek_bar.value = progress;
    current_time.textContent = formatTime(audio.currentTime);
  }
});



// Album card click loads new track
document.querySelectorAll('.album_card').forEach(card => {
  card.addEventListener('click', () => {
    const artist = card.querySelector('.artist_name').textContent.trim();
    const album = card.querySelector('.album_name').textContent.trim();
    const newSrc = `src/${artist}/${album}/sample.mp3`;
    const newTitle = `${artist} — ${album}`;

    loadTrack(newSrc, newTitle);
  });
});