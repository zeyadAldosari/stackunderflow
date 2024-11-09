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
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
      <div class="px-3 py-3">
        <div class="flex items-center justify-between mr-16">
          <div class="flex items-center justify-start">
            <a href="https://flowbite.com" class="flex ms-2 md:me-24">
              <span class="self-center font-semibold text-2xl whitespace-nowrap"
                >Logo</span
              >
            </a>
          </div>
          <div class="flex items-center">
            <div class="flex items-center ms-3 gap-4">
              <img
                class="w-12 h-12 rounded-full"
                src="../image/avatar.svg"
                alt="user photo" />
              <span class="text-gray-800 font-medium text-xl">Mohammed</span>
            </div>
          </div>
        </div>
      </div>
    </nav>
    <aside
      id="logo-sidebar"
      class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform bg-white border-r border-gray-200"
      aria-label="Sidebar">
      <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
        <ul class="space-y-2 font-medium">
          <li>
            <a
              href="#"
              class="flex items-center p-2 text-gray-800 rounded-lg hover:bg-gray-100">
              <svg
                class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-800"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                fill="currentColor"
                viewBox="0 0 24 24">
                <path
                  fill-rule="evenodd"
                  d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm9.008-3.018a1.502 1.502 0 0 1 2.522 1.159v.024a1.44 1.44 0 0 1-1.493 1.418 1 1 0 0 0-1.037.999V14a1 1 0 1 0 2 0v-.539a3.44 3.44 0 0 0 2.529-3.256 3.502 3.502 0 0 0-7-.255 1 1 0 0 0 2 .076c.014-.398.187-.774.48-1.044Zm.982 7.026a1 1 0 1 0 0 2H12a1 1 0 1 0 0-2h-.01Z"
                  clip-rule="evenodd" />
              </svg>

              <span class="ms-3">Questions</span>
            </a>
          </li>
          <li>
            <a
              href="myQuestions.php"
              class="flex items-center p-2 text-gray-800 rounded-lg hover:bg-gray-100">
              <svg
                class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-800"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                fill="currentColor"
                viewBox="0 0 24 24">
                <path
                  fill-rule="evenodd"
                  d="M2 6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6Zm4.996 2a1 1 0 0 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 8a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Zm-4.004 3a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 11a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Zm-4.004 3a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 14a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Z"
                  clip-rule="evenodd" />
              </svg>

              <span class="flex-1 ms-3 whitespace-nowrap">My questions</span>
            </a>
          </li>
          <li>
            <a
              href="myAnswers.html"
              class="flex items-center p-2 text-gray-800 rounded-lg hover:bg-gray-100">
              <svg
                class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-800"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                fill="currentColor"
                viewBox="0 0 24 24">
                <path
                  fill-rule="evenodd"
                  d="M18 14a1 1 0 1 0-2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2v-2Z"
                  clip-rule="evenodd" />
                <path
                  fill-rule="evenodd"
                  d="M15.026 21.534A9.994 9.994 0 0 1 12 22C6.477 22 2 17.523 2 12S6.477 2 12 2c2.51 0 4.802.924 6.558 2.45l-7.635 7.636L7.707 8.87a1 1 0 0 0-1.414 1.414l3.923 3.923a1 1 0 0 0 1.414 0l8.3-8.3A9.956 9.956 0 0 1 22 12a9.994 9.994 0 0 1-.466 3.026A2.49 2.49 0 0 0 20 14.5h-.5V14a2.5 2.5 0 0 0-5 0v.5H14a2.5 2.5 0 0 0 0 5h.5v.5c0 .578.196 1.11.526 1.534Z"
                  clip-rule="evenodd" />
              </svg>

              <span class="flex-1 ms-3 whitespace-nowrap">My answers</span>
            </a>
          </li>
          <li>
            <a
              href="createQuestion.html"
              class="flex items-center p-2 text-gray-800 rounded-lg hover:bg-gray-100">
              <svg
                class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-800"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                fill="currentColor"
                viewBox="0 0 24 24">
                <path
                  fill-rule="evenodd"
                  d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4.243a1 1 0 1 0-2 0V11H7.757a1 1 0 1 0 0 2H11v3.243a1 1 0 1 0 2 0V13h3.243a1 1 0 1 0 0-2H13V7.757Z"
                  clip-rule="evenodd" />
              </svg>

              <span class="flex-1 ms-3 whitespace-nowrap">Create Question</span>
            </a>
          </li>
          <li>
            <a
              href="index.html"
              class="flex items-center p-2 text-gray-800 rounded-lg hover:bg-gray-100">
              <svg
                class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-800"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 18 16">
                <path
                  stroke="currentColor"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3" />
              </svg>
              <span class="flex-1 ms-3 whitespace-nowrap">Log out</span>
            </a>
          </li>
        </ul>
      </div>
    </aside>
    <main class="p-14 ml-64">
      <div
        class="w-full p-6 bg-white border border-gray-200 rounded-3xl shadow my-16">
        <div class="flex items-center gap-4 mb-5">
          <img
            class="w-20 h-20 rounded-full"
            src="../../image/avatar.svg"
            alt="" />
          <div>
            <a href="#">
              <h5 class="text-2xl font-semibold tracking-tight text-gray-800">
                Run java program
              </h5>
            </a>
            <div class="text-lg text-gray-500 font-medium">Mohammed</div>
          </div>
        </div>

        <p class="mb-3 text-black px-3">
          I haven't programmed much in Java, I once had to write a few
          applications for school, and it wasn't a problem to run it, now I
          would like to develop it and look for some other projects, (MyLocalTon
          to be more precise) I see that it uses Java 11, from what I read Maven
          is from Java 17, and I have no idea how to run such a project, where
          to get information on how to run it, where I can learn such things. I
          have a pom.mi file that points to Maven, but during installation, when
          I have Java 11, it throws a lot of errors, then I change it to 17, it
          seems like something is happening, but I still have errors. I've
          already downloaded skman so I don't have to mess with the versions too
          much. I have a question: does anyone know where I can find answers to
          my question? Here's a link to the project I'm interested in:
          <a href="" class="underline"
            >https://github.com/neodix42/MyLocalTon</a
          >
        </p>
      </div>

      <form>
        <label
          for="comment"
          class="block mb-2 text-2xl font-semibold text-gray-800"
          >Answer description</label
        >
        <div class="w-full mb-4 border border-gray-200 rounded-lg bg-gray-50">
          <div class="px-4 py-4 bg-white rounded-t-lg">
            <textarea
              id="comment"
              rows="8"
              class="w-full px-0 text-lg text-gray-900 bg-white border-0 focus:outline-none"
              placeholder="Write your answer description..."
              required></textarea>
          </div>
          <div class="flex items-center justify-between px-3 py-2 border-t">
            <button
              type="submit"
              class="inline-flex items-center py-2.5 px-4 text-base font-medium text-center text-white bg-gray-800 rounded-lg hover:bg-gray-900">
              Post answer
            </button>
          </div>
        </div>
      </form>
    </main>
  </body>
</html>
