<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">
    <h3>BLOGIFY</h3>
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarText">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="../blog/feed?bearers=eHR0cHM6XC9cL2xvY2FsaG9zdCJdLCJleHAiOjE2N">FEED </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../blog/myfeed?bearers=eHR0cHM6XC9cL2xvY2FsaG9zdCJdLCJleHAiOjE2N">MY FEED</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../blog/index?bearers=eHR0cHM6XC9cL2xvY2FsaG9zdCJdLCJleHAiOjE2N">ADD BLOG</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0" action="../setting" method="POST">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
      <!-- <form action="../controllers/setting" method="POST"> -->
        <select class="mx-2 p-2 border-rounded bg-dark text-light" name="setting" onchange="this.form.submit()">
          <option selected disabled>SETTTINGS</option>
          <option value="logout">Log out</option>
          <option value="deactivate">Deactivate my account</option>
        </select>
      <!-- </form> -->
    </form>
  </div>
</nav>