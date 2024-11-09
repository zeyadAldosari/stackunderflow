<?php
include 'authCheck.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Project 381</title>
  <link href="./output.css" rel="stylesheet" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet" />
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
  <?php
  include './components/nav.php'
  ?>
  <?php include './components/sideBar.php'; ?>

  <main class="p-14 ml-64">
    <div class="flex items-start justify-between my-20">
      <h1 class="text-gray-800 font-bold text-5xl">Create question</h1>
    </div>
    <?php if (isset($_SESSION['success_message'])) : ?>
      <div class="text-green-400 bg-green-200 p-2 rounded-md" onclick="this.classList.toggle('hidden')">
        <div>
          <?= $_SESSION['success_message']; ?>
          <?php unset($_SESSION['success_message']); ?>
        </div>
      </div>
    <?php endif; ?>

    <form action="create_question_script.php" method="post">
      <div class="mb-10">
        <label for="large-input" class="block mb-2 text-2xl font-semibold text-gray-800">Question title</label>
        <input type="text" id="large-input" name="title" class="block w-full p-4 text-gray-800 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none text-xl font-semibold" placeholder="Write your question title..." required />
      </div>
      <label for="comment" class="block mb-2 text-2xl font-semibold text-gray-800">Question description</label>
      <div class="w-full mb-4 border border-gray-200 rounded-lg bg-gray-50">
        <div class="px-4 py-4 bg-white rounded-t-lg">
          <textarea id="comment" rows="8" name="content" class="w-full px-0 text-lg text-gray-900 bg-white border-0 focus:outline-none" placeholder="Write your question description..." required></textarea>
        </div>
        <div class="flex items-center justify-between px-3 py-2 border-t">
          <button type="submit" class="inline-flex items-center py-2.5 px-4 text-base font-medium text-center text-white bg-gray-800 rounded-lg hover:bg-gray-900">
            Post question
          </button>
        </div>
      </div>
    </form>
  </main>
</body>

</html>