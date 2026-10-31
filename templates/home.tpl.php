<?php
require_once __DIR__ . '/header.tpl.php';
?>

  <h1>URL Shortener</h1>
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
    <button type="submit">Shorten</button>
  </form>

  <script>
    const submitForm = async (urlValue) => {
      const res = await fetch("/create", {
        method: "POST",
        body: {
          url: urlValue
        }
      });
      const resJson = await res.json();
      if (!resJson.ok) {
        throw Error("Error with form submission");
      }

      return resJson;
    }

    (() => {
      const form = document.getElementById("create-short-url");
      form.addEventListener("submit", (e) => {
        e.preventDefault();
        const value = e.currentTarget.querySelector("#url").value;
        submitForm(value).then(console.log).catch(console.error)
      });
    })();
  </script>

<?php
require_once __DIR__ . '/footer.tpl.php';
