const statusMessage = document.querySelector('#player-status');
const audioPlayer = document.querySelector('#audio-player');
const playlist = document.querySelector('#playlist');
const titleLabel = document.querySelector('#track-title');
const artistLabel = document.querySelector('#track-artist');
const previousButton = document.querySelector('#previous-track');
const nextButton = document.querySelector('#next-track');

let songs = [];
let currentIndex = 0;

function selectTrack(index) {
    currentIndex = index;
    const song = songs[currentIndex];
    titleLabel.textContent = song.title;
    artistLabel.textContent = song.artist;
    audioPlayer.src = song.src;
    audioPlayer.load();
    statusMessage.textContent = `Pista ${currentIndex + 1} de ${songs.length}`;
    playlist.querySelectorAll('button').forEach((button, buttonIndex) => {
        button.setAttribute('aria-current', buttonIndex === currentIndex ? 'true' : 'false');
    });
}

function renderPlaylist() {
    playlist.replaceChildren();
    songs.forEach((song, index) => {
        const item = document.createElement('li');
        const button = document.createElement('button');
        button.type = 'button';
        button.textContent = `${song.title} — ${song.artist}`;
        button.addEventListener('click', () => selectTrack(index));
        item.append(button);
        playlist.append(item);
    });
}

async function loadSongs() {
    try {
        const response = await fetch('./api/canciones.php', {
            headers: { Accept: 'application/json' },
        });
        if (!response.ok) {
            throw new Error(response.status === 401 ? 'Inicia sesión para cargar las canciones.' : 'No se pudo cargar el catálogo.');
        }

        const result = await response.json();
        if (!Array.isArray(result) || !result.every((song) => (
            song !== null
            && Number.isInteger(song.id)
            && typeof song.title === 'string'
            && typeof song.artist === 'string'
            && typeof song.src === 'string'
            && ['audio/demo-01.mp3', 'audio/demo-02.mp3'].includes(song.src)
        ))) {
            throw new Error('La respuesta del catálogo no tiene un formato válido.');
        }

        songs = result;
        if (songs.length === 0) {
            statusMessage.textContent = 'Todavía no hay canciones en el catálogo.';
            previousButton.disabled = true;
            nextButton.disabled = true;
            audioPlayer.removeAttribute('src');
            playlist.replaceChildren();
            return;
        }

        renderPlaylist();
        previousButton.disabled = false;
        nextButton.disabled = false;
        selectTrack(0);
    } catch (error) {
        statusMessage.textContent = error instanceof Error ? error.message : 'Error al cargar el catálogo.';
        previousButton.disabled = true;
        nextButton.disabled = true;
    }
}

previousButton.addEventListener('click', () => {
    if (songs.length > 0) {
        selectTrack((currentIndex - 1 + songs.length) % songs.length);
    }
});

nextButton.addEventListener('click', () => {
    if (songs.length > 0) {
        selectTrack((currentIndex + 1) % songs.length);
    }
});

audioPlayer.addEventListener('error', () => {
    statusMessage.textContent = 'No se pudo cargar el audio. Comprueba que el archivo autorizado está en docker/src/audio/.';
});

loadSongs();
