<html>
  <head>
    <link rel="stylesheet" href="style.php">
  <head>
  <body>
    <div class="onboarding">
      <div class="onboarding-overlay">
        <div class="onboarding-overlay-outer">
          <div class="onboarding-overlay-inner returning">
            <img class="returning__image" src="images/logo.png" alt="Horse logo">
            <h1 class="returning__header">Sign in to Farrier&nbsp;Site</h1>
            <form {{action "login" on="submit"}} class="signin">
              <div class="form-group">
                <label for="email_or_username">Username</label>
                <Input class="form-control" id="email_or_username" aria-describedby="emailHelp" />
              </div>
              <br></br> 
              <div class="form-group">
                <label for="password">Password</label>
                <Input type="password" class="form-control" id="password" aria-describedby="emailHelp" />
              </div>
              <!-- {{#if error}}
                <br>
                <small class="onboarding-form__small"><FaIcon @icon="exclamation-triangle" /> Something's wrong. Please check your email address and password.</small>
              {{/if}} -->
              <button class="onboarding-form__btn returning__btn" type="submit">Sign in</button>
              <label class="signup"> Not a member? <a href="signup.php">Sign up</a> now!</label>
            </form>
          </div>
          <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
        </div>
      </div>
    </div>
  </body>
</html>