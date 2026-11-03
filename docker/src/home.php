<?php

require_once __DIR__ . '/lib/acceso.php';
require_once __DIR__ . '/lib/canciones.php';
require_once __DIR__ . '/lib/vista.php';

iniciarPagina('Jukebox');
?>
<p>Hola, <?= escapar($_SESSION['alias']) ?>.</p>
<nav>
    <a href="canciones.php">Gestionar catálogo</a>
</nav>
<section aria-labelledby="player-title">
    <h2 id="player-title">Reproductor</h2>
    <p id="player-status" role="status">Cargando catálogo…</p>
    <p id="track-title"></p>
    <p id="track-artist"></p>
    <audio id="audio-player" controls preload="metadata"></audio>
    <div>
        <button id="previous-track" type="button" disabled>Anterior</button>
        <button id="next-track" type="button" disabled>Siguiente</button>
    </div>
    <ol id="playlist"></ol>
</section>
<form method="post" action="logout.php">
    <input type="hidden" name="csrf" value="<?= escapar(tokenCsrf()) ?>">
    <button type="submit">Cerrar sesión</button>
</form>
<script src="scripts/reproductor.js" defer></script>
<?php finalizarPagina(); ?>
