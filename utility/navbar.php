<?
echo '<nav class="navbar navbar-expand-md navbar-light bg-light">
  <a class="navbar-brand" href="../utility/router.php">Home</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="../utility/router.php?to=addExercise">Új gyakorlat</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../utility/router.php?to=addWorkout">Új edzés</a>
      </li>
      <li class="nav-item">
		<a class="nav-link" href="../utility/router.php?to=summary">Összegzés</a>
	  </li>
    </ul>
	<ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="../utility/router.php?to=profile">Profilom</a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="../utility/router.php?to=logout">Kijelentkezés</a>
      </li>
    </ul>
  </div>
</nav>';
?>