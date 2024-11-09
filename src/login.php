<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Project 381</title>
    <link href="./output.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap"
      rel="stylesheet" />
    <style>
      * {
        font-family: "Inter", sans-serif;
      }
      .main-color {
        color: #313131;
      }
    </style>
  </head>
  <body class="bg-gray-200">
    <section>
      <div
        class="flex flex-col items-center justify-center px-6 py-8 mx-auto h-screen lg:py-0">
        <a
          href="#"
          class="flex items-center mb-6 text-2xl font-semibold text-gray-900">
          stackunderflow
        </a>
        <div class="w-full bg-white rounded-lg shadow mt-0 max-w-md p-0">
          <div class="space-y-6 p-8">
            <h1
              class="font-bold leading-tight tracking-tight text-gray-800 text-2xl">
              Log in to your account
            </h1>
            <form class="space-y-6" action="auth.php" method="post">
              <div>
                <label
                  for="email"
                  class="block mb-2 text-sm font-medium text-gray-800"
                  >Your username</label
                >
                <input
                  type="username"
                  name="username"
                  id="username"
                  class="bg-gray-50 border border-gray-300 text-gray-800 text-sm rounded-lg focus:outline-none block w-full p-2.5"
                  placeholder="username"
                  required="" />
              </div>
              <div>
                <label
                  for="password"
                  class="block mb-2 text-sm font-medium text-gray-800"
                  >Password</label
                >
                <input
                  type="password"
                  name="password"
                  id="password"
                  placeholder="••••••••"
                  class="bg-gray-50 border border-gray-300 text-gray-800 text-sm rounded-lg block focus:outline-none w-full p-2.5"
                  required="" />
              </div>
              <div id="error"></div>
              <?php
                  session_start();

                  if(isset($_SESSION['error_message'])) {
                      echo "<p class=text-red-500>{$_SESSION['error_message']}</p>";            
                      unset($_SESSION['error_message']);
                  }
                  ?>
              <button
                type="submit"
                class="w-full text-white bg-gray-800 hover:bg-gray-900 font-medium rounded-lg text-base px-5 py-2.5 text-center">
                Log in
              </button>
              <p class="text-sm font-light text-gray-500">
                Don’t have an account yet?
                <a href="signup.php" class="font-medium text-primary-600 hover:underline"
                  >Sign up</a
                >
              </p>
            </form>
          </div>
        </div>
      </div>
    </section>
    
  </body>
</html>

