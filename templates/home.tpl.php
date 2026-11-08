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

  <div id="urls">
    <p>No URLs yet...</p>
  </div>

  <script>
    // State
    let state = {
      messages: [],
      isFetchingUrls: false,
      urls: []
    };

    // Components
    const form = document.getElementById("create-short-url");
    const messagesWrapper = document.getElementById("messages");
    const urlsListWrapper = document.getElementById("urls");

    // Render URLs list
    const renderUrlsList = () => {
      if (!state.urls.length) {
        urlsListWrapper.innerHTML = `<p>No URLs yet...</p>`;
        return;
      }

      urlsListWrapper.innerHTML = "";
      state.urls.forEach(url => {
        urlsListWrapper.insertAdjacentHTML("beforeend", `
          <article>
            <h4>${decodeURIComponent(url.short_version)}</h4>
            <ul>
              <li>Original: ${decodeURIComponent(url.original_url)}</li>
              <li>Visit Count: ${url.visit_count}</li>
              <li>Created: ${url.created_at}</li>
              <li>Updated: ${url.updated_at}</li>
            </ul>
            <button class="delete" type="button" data-target="${url.id}"><u>Delete</u></button>
          </article>
        `);
      });
    };

    // Get URLs
    const getUrls = async () => {
      if (state.isFetchingUrls) {
        return;
      }
      console.log(1, state.urls);

      try {
        state.isFetchingUrls = true;

        const res = await fetch("/urls");
        const resJson = await res.json();
        if (!resJson.ok) {
          throw Error("Error with fetching URLS");
        }

        state.urls = resJson.data.data;
        return resJson;
      } finally {
        state.isFetchingUrls = false;
        renderUrlsList();
      }
    };

    // Update state
    const updateState = () => {
      getUrls();

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
        state.messages = [];
      } else {
        messagesWrapper.innerHTML = "";
        messagesWrapper.setAttribute("hidden", "hidden");
      }
    };

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
    };

    updateState();
    getUrls();

    form.addEventListener("submit", (e) => {
      e.preventDefault();
      form.setAttribute("data-is-submitting", "");

      submitForm({
        originalUrl: e.currentTarget.querySelector("#url").value,
        shortVersion: e.currentTarget.querySelector("#short_version").value
      })
        .then(data => {
          if (data?.messages?.length) {
            data.messages.forEach(m => {
              state.messages.push(m);
            });
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
          form.removeAttribute("data-is-submitting");
          updateState();
        });
    });

    document.addEventListener("click", async event => {
      const {target} = event;
      const deleteBtn = target.closest("button.delete[data-target]");
      if (deleteBtn) {
        if (confirm("Do you want to delete this short URL?")) {
          try {
            const res = await fetch("/delete", {
              method: "DELETE",
              body: JSON.stringify({
                id: deleteBtn.dataset.target,
              })
            });
            const json = await res.json();
            if (json?.messages?.length) {
              json.messages.forEach(m => {
                state.messages.push(m);
              });
            }
          } catch (e) {
            state.messages.push({
              type: "error",
              message: "Something went wrong. Try again."
            });
          } finally {
            updateState();
          }
        }
      }
    });
  </script>

<?php
require_once __DIR__ . '/footer.tpl.php';
