<h1>S'inscrire</h1>
<form method="post">

<label for="last_name">Nom :</label><br>
<input type="text" name="last_name" id="last_name"><br>

<label for="first_name">Prénom :</label><br>
<input type="text" name="first_name" id="first_name"><br>

    <label for="email">Email :</label><br>
    <input type="email" name="email" id="email"><br>

    <label for="password">Mot de passe :</label><br>
    <input type="password" name="password" id="password">
    <br>

    <label for="password_confirm">Répétez votre mot de passe :</label><br>
    <input type="password" name="password_confirm" id="password_confirm">
    <br>

    <input type="hidden" name="csrf_token" value="<?=$_SESSION['csrf_token']?>">

    <button>S'inscrire</button>
</form>