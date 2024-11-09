<nav class="fixed top-0 z-10 w-full bg-white border-b border-gray-400">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
      <img src="../images/logo.png" class="h-20" alt="StackUnderflow Logo">
      <span class="text-2xl font-semibold whitespace-nowrap">stackunderflow</span>
    </a>
    <div class="flex space-x-2" id="navbar">
      <button type="button" class="text-gray-800 border border-gray-800 hover:bg-gray-600 hover:text-white focus:ring-4 focus:outline-none focus:ring-gray-500 font-medium rounded-lg text-base px-4 py-2 text-center">
        <a href="../login.php">Log in</a>
      </button>
      <button type="button" class="text-white bg-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-500 font-medium rounded-lg text-base px-4 py-2 text-center">
        <a href="../signup.php">Sign up</a>
      </button>
    </div>
  </div>
</nav>
<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}


if (isset($_SESSION['username'])) {
  $username = $_SESSION['username'];

  echo <<<EOL
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('navbar').innerHTML = '<a href=\"../createQuestion.php\" class="p-2 underline text-xl font-bold">$username</a>';
    });

</script>
EOL;
}
?>