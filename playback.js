const audio=document.getElementById('audio-player')
const play_pause=document.getElementById('play_pause')
const seek_bar=document.getElementById('seek_bar')
const current_time=document.getElementById('current_time')
const duration=document.getElementById('duration')
const track_name=document.getElementById('track_name')

currentTrack="src/cat-meow-321642.mp3"

//query selector for the spotlight album cards

document.querySelectorAll('.album_card').forEach(card => {
    card.addEventListener('click',()=>{
        const artist=card.querySelector('.artist_name').textContent;
        const album=card.querySelector('.album_name').textContent;
        currentTrack=`src/${artist}/${album}/smaple.mp3`;
        console.log(currentTrack);
        // console.log(`src/${artist}/${album}/smaple.mp3`);
    })    
});


