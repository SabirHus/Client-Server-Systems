class SearchSuggestion {
    constructor(inputId, suggestionBoxId, outputId, searchURL) {
        this.input = document.getElementById(inputId);
        this.suggestionBox = document.getElementById(suggestionBoxId);
        this.output = document.getElementById(outputId);
        this.searchURL = searchURL;

        this.seen = new Set(); // To prevent duplicate suggestions
        this.init(); // Set up search event handling
    }

    /**
     * Bind the keyup event on the search input.
     */
    init() {
        this.input.addEventListener("keyup", () => this.onSearchInput());
    }

    /**
     * Handle input changes: clear if empty or send AJAX request.
     */
    onSearchInput() {
        const str = this.input.value.trim();

        if (str.length === 0) {
            this.clearSuggestions();     // Hide suggestions if input is empty
            this.output.innerHTML = "";  // Clear result display
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = () => {
            if (xhr.readyState === 4 && xhr.status === 200) {
                this.renderResults(xhr.responseText); // Parse and display XML results
            }
        };

        xhr.open("GET", `${this.searchURL}?q=${encodeURIComponent(str)}`, true);
        xhr.send();
    }

    /**
     * Parse the XML and delegate rendering to both suggestions and cards.
     * @param {string} xmlString - The XML response as string.
     */
    renderResults(xmlString) {
        const parser = new DOMParser();
        const xmlDoc = parser.parseFromString(xmlString, "text/xml");
        const facilities = xmlDoc.getElementsByTagName("facility");

        this.renderSuggestions(facilities);
        this.renderCards(facilities);
    }

    /**
     * Render clickable search suggestion items under the input.
     * @param {HTMLCollection} facilities - XML elements representing search results.
     */
    renderSuggestions(facilities) {
        this.suggestionBox.innerHTML = "";
        this.seen.clear();

        Array.from(facilities).forEach(f => {
            const title = f.getElementsByTagName("title")[0].textContent;
            const category = f.getElementsByTagName("category")[0].textContent;
            const town = f.getElementsByTagName("town")?.[0]?.textContent ?? "";

            // Avoid duplicate suggestion values
            [title, category, town].forEach(value => {
                const lower = value.toLowerCase();
                if (value && !this.seen.has(lower)) {
                    const li = document.createElement("li");
                    li.className = "list-group-item list-group-item-action";
                    li.innerHTML = `<a href="?search=${encodeURIComponent(value)}" class="text-decoration-none d-block">${value}</a>`;
                    this.suggestionBox.appendChild(li);
                    this.seen.add(lower);
                }
            });
        });

        // Hide the suggestion box if no results found
        this.suggestionBox.classList.toggle("d-none", facilities.length === 0);
    }

    /**
     * Display full result cards beneath the search bar.
     * @param {HTMLCollection} facilities - XML facility elements.
     */
    renderCards(facilities) {
        this.output.innerHTML = "";

        Array.from(facilities).forEach(f => {
            const title = f.getElementsByTagName("title")[0].textContent;
            const category = f.getElementsByTagName("category")[0].textContent;
            const town = f.getElementsByTagName("town")?.[0]?.textContent ?? "";
            const desc = f.getElementsByTagName("description")[0].textContent;

            const col = document.createElement("div");
            col.className = "col-md-6 mb-3";
            col.innerHTML = `
        <a href="?search=${encodeURIComponent(title)}" class="text-decoration-none">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title">${title}</h5>
              <h6 class="card-subtitle text-muted mb-2">${category} — ${town}</h6>
              <p class="card-text">${desc}</p>
            </div>
          </div>
        </a>
      `;
            this.output.appendChild(col);
        });
    }

    /**
     * Clear and hide the suggestion dropdown box.
     */
    clearSuggestions() {
        this.suggestionBox.innerHTML = "";
        this.suggestionBox.classList.add("d-none");
    }
}
