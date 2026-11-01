<?php
require_once __DIR__ . '/header.tpl.php';
?>

  <h1>URL Shortener</h1>

  <div hidden id="messages"></div>

  <form action="/create" id="create-short-url" method="post">
    <label>
      <span>URL:</span>
      <input
        type="url"
        name="url"
        id="url"
        placeholder="https://example.com"
        pattern="https://.*"
        size="30"
        required
      >
    </label>
    <br>
    <br>
    <label>
      <span>Short Code:</span>
      <input
        type="text"
        name="short_version"
        id="short_version"
        placeholder="/shortversion"
        size="30"
        required
      >
    </label>
    <br>
    <br>
    <button type="submit">Shorten</button>
  </form>

  <script>
    // State
    let state = {
      isSubmitting: false,
      messages: [],
    };

    // Components
    const form = document.getElementById("create-short-url");
    const messagesWrapper = document.getElementById("messages");

    // Update state
    const updateState = () => {
      // Submitting state
      if (state.isSubmitting) {
        form.setAttribute("data-is-submitting", "");
      } else {
        form.removeAttribute("data-is-submitting");
      }

      // Status messages
      messagesWrapper.innerHTML = "";
      if (state.messages.length) {
        messagesWrapper.removeAttribute("hidden");
        state.messages.forEach(m => {
          messagesWrapper.insertAdjacentHTML(
            "beforeend",
            `<div class="message" data-type="${m.type}">${m.message}</div>`
          );
        });
      } else {
        messagesWrapper.innerHTML = "";
        messagesWrapper.setAttribute("hidden", "hidden");
      }
    }

    // Submit form
    const submitForm = async (payload) => {
      const res = await fetch("/create", {
        method: "POST",
        body: JSON.stringify(payload)
      });
      const resJson = await res.json();
      if (!resJson.ok) {
        throw Error("Error with form submission");
      }

      return resJson;
    }

    updateState();

    form.addEventListener("submit", (e) => {
      e.preventDefault();
      state.isSubmitting = true;
      updateState();

      submitForm({
        originalUrl: e.currentTarget.querySelector("#url").value,
        shortVersion: e.currentTarget.querySelector("#short_version").value
      })
        .then(data => {
          if (data?.messages?.length) {
            data.messages.forEach(m => {
              console.log({m})
              state.messages.push(m);
            })
          }
        })
        .catch(error => {
          if (error instanceof Error) {
            state.messages.push({
              type: "error",
              message: e.message
            });
          } else {
            console.log("An unexpected error occurred:", error);
            state.messages.push({
              type: "error",
              message: "An unexpected error occurred. Check console."
            });
          }
        })
        .finally(() => {
          state.isSubmitting = false;
          updateState();
        })
    });
  </script>

<?php
require_once __DIR__ . '/footer.tpl.php';
