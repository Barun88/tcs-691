const audio = document.getElementById('audio_player');
const play_pause = document.getElementById('play_pause');
const seek_bar = document.getElementById('seek_bar');
const current_time = document.getElementById('current_time');
const duration = document.getElementById('duration');
const track_name = document.getElementById('track_name');

let isPlaying = false;
let isReady = false;
let isSeeking = false;
let currentPlaylist = [];
let currentTrackIndex = -1;
let playlistType = null; // 'lofi', 'album', etc.

let lofiSongs = [];

// Fetch lofi songs from server
async function fetchLofiSongs() {
  try {
    const response = await fetch('get_lofi_songs.php');
    const songs = await response.json();
    lofiSongs = songs;
    console.log(`Loaded ${songs.length} lofi songs`);
  } catch (error) {
    console.error('Failed to load lofi songs:', error);
    // Fallback - you can add a few songs here if needed
    lofiSongs = [];
  }
}

// Initialize lofi songs when page loads
fetchLofiSongs();

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

// Function to play a random lofi song
function playRandomLofi() {
  if (lofiSongs.length === 0) {
    console.warn('No lofi songs available');
    return;
  }
  
  // Set up lofi playlist
  currentPlaylist = [...lofiSongs];
  playlistType = 'lofi';
  
  const randomIndex = Math.floor(Math.random() * currentPlaylist.length);
  currentTrackIndex = randomIndex;
  
  const randomSong = currentPlaylist[currentTrackIndex];
  const songPath = `src/lofi/${randomSong}`;
  const songName = randomSong.replace('.mp3', '').replace(/-|_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
  
  loadTrack(songPath, `${songName} (Lofi)`);
}

// Function to play next song
function playNext() {
  if (currentPlaylist.length === 0) return;
  
  currentTrackIndex = (currentTrackIndex + 1) % currentPlaylist.length;
  playCurrentTrack();
}

// Function to play previous song
function playPrevious() {
  if (currentPlaylist.length === 0) return;
  
  currentTrackIndex = (currentTrackIndex - 1 + currentPlaylist.length) % currentPlaylist.length;
  playCurrentTrack();
}

// Function to play the current track based on index
function playCurrentTrack() {
  if (currentPlaylist.length === 0 || currentTrackIndex < 0) return;
  
  const currentSong = currentPlaylist[currentTrackIndex];
  let songPath, songName;
  
  if (playlistType === 'lofi') {
    songPath = `src/lofi/${currentSong}`;
    songName = currentSong.replace('.mp3', '').replace(/-|_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    songName = `${songName} (Lofi)`;
  } else {
    // Handle other playlist types (albums, etc.) if needed in the future
    songPath = currentSong.path;
    songName = currentSong.name;
  }
  
  loadTrack(songPath, songName);
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

// Next and Previous button event listeners
document.getElementById('next_btn').addEventListener('click', playNext);
document.getElementById('previous_btn').addEventListener('click', playPrevious);

// Seek bar functionality
seek_bar.addEventListener('input', () => {
  if (isReady) {
    isSeeking = true;
    const seekTime = (seek_bar.value / 100) * audio.duration;
    audio.currentTime = seekTime;
  }
});

seek_bar.addEventListener('change', () => {
  isSeeking = false;
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

    // Reset playlist when playing individual album tracks
    currentPlaylist = [];
    currentTrackIndex = -1;
    playlistType = null;

    loadTrack(newSrc, newTitle);
  });
});

// Add click event to the gif hover card for random lofi
document.getElementById('gif_hover_card').addEventListener('click', () => {
  playRandomLofi();
});

// Optional: Add some visual feedback when clicking the gif
document.getElementById('gif_hover_card').addEventListener('click', (e) => {
  const card = e.currentTarget;
  card.style.transform = 'scale(0.95)';
  setTimeout(() => {
    card.style.transform = 'scale(1)';
  }, 150);
});

// Keyboard controls for next/previous
document.addEventListener('keydown', (e) => {
  // Prevent default if user is not typing in an input field
  if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
    switch(e.key) {
      case 'ArrowRight':
      case 'n':
      case 'N':
        e.preventDefault();
        playNext();
        break;
      case 'ArrowLeft':
      case 'p':
      case 'P':
        e.preventDefault();
        playPrevious();
        break;
      case ' ':
        e.preventDefault();
        play_pause.click();
        break;
    }
  }
});

// Auto-play next song when current song ends
audio.addEventListener('ended', () => {
  if (currentPlaylist.length > 0) {
    playNext();
  }
});