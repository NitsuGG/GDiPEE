<div class="container d-flex justify-content-center mt-5">
  <div class="border rounded col-lg-5 bg-dark p-5">
    <h1 class="text-center text-light">Connexion</h1>
    <form id="login-form" class="mt-5" method="POST">

      <label for="email" class="col-form-label text-light">Email</label>
      <input type="email" name="email" id="email" placeholder="exemple@email.com" class="form-control">

      <label for="password" class="col-form-label text-light">Mot de passe</label>
      <input type="password" name="password" id="password" placeholder="Mot de passe" class="form-control">

      <?=$data_sanitizer->generate_csrf_input()?>
      <button class="btn btn-light mt-2">Se connecter</button>
    </form>
  </div>

</div>